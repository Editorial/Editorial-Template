<?php
require_once locate_template('/admin/metaboxes.php');          // Custom metaboxes
require_once locate_template('/admin/add-media-ui.php');       // Custom add media
require_once locate_template('/admin/plugin-activation.php');  // Plugin activation
// define('SCRIPT_DEBUG', true);
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

/**
 * Editorial Admin class
 *
 * @package     Editorial
 * @copyright   Copyright (c) 2011, Editorial
 * @link        http://www.editorialtemplate.com
 * @version     1.0
 */
class Editorial_Admin
{
    /**
     * Pages
     */
    const PAGE_LOOK = 'look';
    const PAGE_SHARE = 'sharing';
    const PAGE_CUSTOMIZE = 'customstyle';
    const PAGE_TRANSLATIONS = 'translations';
    const PAGE_COMMENTS = 'comments';

    /**
     * Child theme name
     */
    const CHILD_THEME = 'editorial-child';

    /**
     * Pages users are allowed to include
     *
     * @var array
     */
    private $_pages = array(
        self::PAGE_LOOK,
        self::PAGE_SHARE,
        self::PAGE_CUSTOMIZE,
        self::PAGE_TRANSLATIONS,
        self::PAGE_COMMENTS,
    );

    /**
     * Valid options
     *
     * @var array
     */
    private $_options = array(
        'logo-big',
        'logo-small',
        'logo-gallery',
        'favicon',
        'touch-icon',
        'typekit-token',
        'typekit-kit',
        'black-and-white',
        'disable-admin-notices',
        'karma',
        'karma-treshold',
        'twitter-share',
        'twitter-account',
        'facebook-share',
        'google-share',
        'colophon-enabled',
        'copyright',
        'child-theme',
        'pirates',
        'translations',
    );

    /**
     * Current page
     *
     * @var string
     */
    private $_page;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        // setup admin menu
        add_action('admin_menu', array($this, 'menus'));
        /* Fire our meta box setup function on the post editor screen. */
        add_action( 'load-post.php', 'editorial_post_meta_boxes_setup' );
        add_action( 'load-post-new.php', 'editorial_post_meta_boxes_setup' );
        add_filter('media_view_strings', 'editorial_image_tabs', 10, 2);
        add_filter( 'attachment_fields_to_edit', 'editorial_attachment_fields', 10, 2 );
        add_action('wp_ajax_parse_embed_editorial', 'fetch_video');
        remove_filter('attachment_fields_to_edit', array('Editorial','hide_some_attachment_fields'), 11, 2 );
        add_filter( 'attachment_fields_to_edit', 'editorial_attachment_fields_to_edit', 10, 2 );
        // add_action('wp_ajax_editorial_pre_submit_validation', 'editorial_pre_submit_validation');
        // check for update and if the version is valid
        $this->checkUpdate();

        //if child theme was deleted, reset everything
        $has_child = Editorial::getOption( 'child-theme' );
        if ($has_child)
        {
            $child_path = get_theme_root() .'/'. self::CHILD_THEME;
            if ( !is_dir( $child_path ) )
            {
                Editorial::setOption('child-theme', false);
            }
        }

        if (Editorial::getOption('translations') === false)
        {
            //add default translations
            //dump('no translations!');
            Editorial::setOption('translations', Editorial::getTranslations());
        }

        // plugin activation hook
        register_activation_hook('disqus-comment-system/disqus.php', array('Editorial_Admin', 'disqusActivationHook'));
        register_activation_hook('social/social.php', array('Editorial_Admin', 'socialActivationHook'));
        register_activation_hook('facebook/facebook.php', array('Editorial_Admin', 'facebookActivationHook'));
    }

    /**
     * Disqus activation hook
     *
     * @return void
     */
    public static function disqusActivationHook()
    {
        self::pluginActivateHook('disqus');
    }

    /**
     * Social activation hook
     *
     * @return void
     */
    public static function socialActivationHook()
    {
        self::pluginActivateHook('social');
    }

    /**
     * Facebook activation hook
     *
     * @return void
     */
    public static function facebookActivationHook()
    {
        self::pluginActivateHook('facebook');
    }

    /**
     * Activate plugin and deactivate the rest
     *
     * @param  string $type
     * @return void
     */
    public function pluginActivateHook($type)
    {
        if ($type == 'disqus-comment-system')
        {
            $type = 'disqus';
        }

        $all = array(
            'social'   => 'social/social.php',
            'disqus'   => 'disqus-comment-system/disqus.php',
            'facebook' => 'facebook/facebook.php',
        );
        // go through all and deactivate if different from type
        foreach ($all as $plugin => $path)
        {
            if ($plugin != $type)
            {
                if (is_plugin_active($path))
                {
                    // facebook need special care
                    if ($plugin == 'facebook')
                    {
                        if (Editorial::areFacebookCommentsActive())
                        {
                            // remove comment flag for posts
                            self::deactivateFacebookComments();
                        }
                        continue;
                    }
                    // deactivate plugin
                    $tmg = new TGM_Plugin_Activation;
                    $tmg->plugins = array(array(
                        'slug' => $path,
                        'force_deactivation' => true,
                    ));
                    $tmg->force_deactivation();
                    //debug('Deactivate '.$path);
                }
            }
        }
    }

    /**
     * Activate facebook comments
     *
     * @return void
     */
    public static function activateFacebookComments()
    {
        $opt = get_option('facebook_post_features');
        $opt['comments'] = 1;
        update_option('facebook_post_features', $opt);
        update_option('facebook_comments_enabled', 1);
    }

    /**
     * Activate disqus comments
     *
     * @return void
     */
    public static function activateDisqusComments()
    {
        update_option('disqus_active', 1);
    }

    /**
     * Deactivate facebook comments
     *
     * @return void
     */
    public static function deactivateFacebookComments()
    {
        $opt = get_option('facebook_post_features');
        if (array_key_exists('comments', $opt))
        {
            unset($opt['comments']);
        }
        update_option('facebook_post_features', $opt);
    }

    public function child_theme_deleted($data)
    {
        return $data;
    }
    /**
     * Add menu to wordpress administration
     *
     * @return void
     */
    public function menus()
    {
        add_menu_page(
            'Editorial',
            '<strong>Edit</strong>orial',
            'administrator',
            'editorial',
            array($this, 'lookAndFeel'),
            get_bloginfo('template_directory').'/favicon.ico'
        );
        add_submenu_page(
            'editorial',
            'Look & Feel',
            'Look & Feel',
            'administrator',
            'editorial',
            array($this, 'lookAndFeel')
        );
        add_submenu_page(
            'editorial',
            'Comments',
            'Comments',
            'administrator',
            'editorial-'.self::PAGE_COMMENTS,
            array($this, 'comments')
        );
        add_submenu_page(
            'editorial',
            'Sharing',
            'Sharing',
            'administrator',
            'editorial-'.self::PAGE_SHARE,
            array($this, 'sharing')
        );
        add_submenu_page(
            'editorial',
            'Customize',
            'Customize',
            'administrator',
            'editorial-'.self::PAGE_CUSTOMIZE,
            array($this, 'customize')
        );
        add_submenu_page(
            'editorial',
            'Translations',
            'Translations',
            'administrator',
            'editorial-'.self::PAGE_TRANSLATIONS,
            array($this, 'translations')
        );
    }

    /**
     * Look &Feel
     *
     * @return void
     */
    public function lookAndFeel()
    {
        // show look & feel page
        $this->_display(self::PAGE_LOOK);
    }

    /**
     * Comments
     *
     * @return void
     */
    public function comments()
    {
        // show comments
        $this->_display(self::PAGE_COMMENTS);
    }

    /**
     * Sharing settings
     *
     * @return void
     */
    public function sharing()
    {
        $this->_display(self::PAGE_SHARE);
    }

    public function customize()
    {
        $this->_display(self::PAGE_CUSTOMIZE);
    }

    public function translations()
    {
        $this->_display(self::PAGE_TRANSLATIONS);
    }

    /**
     * Display admin page
     *
     * @param  string $page page to display
     * @return void
     */
    private function _display($page = '')
    {
        if (!in_array($page, $this->_pages) || !current_user_can('administrator'))
        {
            wp_die( __('You do not have sufficient permissions to access this page.', 'Editorial'));
        }
        // display intended page
        $this->_page = $page;

        // force typekit?
        if (array_key_exists('typekit', $_GET))
        {
            $this->typekit();
        }

        // handle posts
        $this->_handlePost();

        // add font notice
        if (!Editorial::getOption('typekit-kit'))
        {
            $this->_showNotice(__('<strong>Editorial Typekit fonts are currently disabled.</strong> <a href="admin.php?page=editorial">Enable them</a> to get the most out of the Editorial theme.', 'Editorial'));
        }

        // if black and white option is selected check that we can create them
        if (Editorial::getOption('black-and-white'))
        {
            if (!Editorial::canCreateBWImages())
            {
                $this->_showNotice(__('<strong>Black &amp; white images are disabled</strong>. Please make sure the PHP GD library is installed.', 'Editorial')." ");
-                // disable bw photos for now, the user will get notified of the error
-                Editorial::setOption('black-and-white', false);
            }
        }

        // include template settings page
        include 'settings.php';
    }

    /**
     * Handle post
     *
     * @return void
     */
    private function _handlePost()
    {
        // handle posts
        if (is_array($_POST) && count($_POST))
        {
            $this->_save();
        }
    }

    /**
     * Save form data
     *
     * @return void
     */
    private function _save()
    {
        //dump( $_FILES );
        foreach ($_POST as $key => $value)
        {
            // make sure only allowed settings get in
            if (in_array($key, $this->_options))
            {
                $typekit = false;
                if ($key == 'typekit-token' && $value != Editorial::getOption('typekit-token'))
                {
                    // run typekit
                    $typekit = true;
                }
                // save
                Editorial::setOption($key, $value);
                // run typekit setup
                if ($typekit)
                {
                    $this->typekit();
                }
            }
        }

        // on/off values are special
        switch ($this->_page)
        {
            case self::PAGE_LOOK:
                // checkboxes are special
                $checkboxes = array(
                    'black-and-white',
                );
                $this->_handleCheckboxes($checkboxes);
                $this->_saveLogoImages( $_FILES );
                break;
            case self::PAGE_SHARE:
                $checkboxes = array(
                    'twitter-share',
                    'facebook-share',
                    'google-share',
                    //'readability-share',
                );
                $this->_handleCheckboxes($checkboxes);
                break;
            case self::PAGE_COMMENTS:
                $checkboxes = array(
                    'karma',
                );
                $this->_handleCheckboxes($checkboxes);
                break;
            case self::PAGE_CUSTOMIZE:
                if ($_POST['create-theme']) {
                    Editorial::setOption('child-theme', true);
                    //create child theme
                    $this->_create_child_theme();
                }
                elseif($_POST['child-style-update'])
                {
                    //Editorial::setOption('child-theme', false);
                    $this->_update_custom_style($_POST['child-style-update']);
                }
                break;
            case self::PAGE_TRANSLATIONS:
                //dump($_POST);
                Editorial::setOption('translations', stripslashes_deep($_POST['translations']));
                break;

        }
    }

    private function _saveLogoImages( $files )
    {
        //dump( $files );
        $uploadfiles = $files['logo-image'];

      if (is_array($uploadfiles)) {
          foreach ($uploadfiles['name'] as $key => $value)
          {
              // look only for uploded files
          if ($uploadfiles['error'][$key] == 0)
          {

            $filetmp = $uploadfiles['tmp_name'][$key];

            //clean filename and extract extension
            $filename = $uploadfiles['name'][$key];
            $filetype = wp_check_filetype( basename( $filename ), null );
            $filetitle = preg_replace('/\.[^.]+$/', '', basename( $filename ) );
            $filename = $filetitle . '.' . $filetype['ext'];
            $upload_dir = wp_upload_dir();

            /**
         * Check if the filename already exist in the directory and rename the
         * file if necessary
         */
            $i = 0;
            while ( file_exists( $upload_dir['path'] .'/' . $filename ) ) {
              $filename = $filetitle . '_' . $i . '.' . $filetype['ext'];
              $i++;
            }
            $filedest = $upload_dir['path'] . '/' . $filename;

                 /**
             * Check write permissions
             */
            if ( !is_writeable( $upload_dir['path'] ) ) {
              $this->msg_e('Unable to write to directory %s. Is this directory writable by the server?');
              return;
            }

            /**
             * Save temporary file to uploads dir
             */
            if ( !@move_uploaded_file($filetmp, $filedest) ){
              $this->msg_e("Error, the file $filetmp could not moved to : $filedest ");
              continue;
            }

            $attachment = array(
              'post_mime_type' => $filetype['type'],
              'post_title' => $filetitle,
              'post_content' => '',
              'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attachment, $filedest );
            require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $filedest );
            wp_update_attachment_metadata( $attach_id,  $attach_data );

            Editorial::setOption($key, wp_get_attachment_url( $attach_id ) );

            }
          }
      }
    }

    private function _update_custom_style( $css )
    {
        $theme_root = get_theme_root();
      //ATTENTION, This is hardcoded and it is assuming the child theme is in dir editorial-child
      $style_path = $theme_root.'/editorial-child/style.css';
      file_put_contents( $style_path, stripcslashes($css) );

    }

    private function _create_child_theme()
    {
        $this_theme_title = get_current_theme();
        $this_theme_template = get_template();
        $this_theme_name = get_stylesheet();

        $child_theme_name = self::CHILD_THEME;

        $theme_root = get_theme_root();

        // Validate theme name
        $new_theme_path = $theme_root.'/'.$child_theme_name;
        if ( file_exists( $child_theme_name ) ) {
            return new WP_Error( 'exists', __( 'Theme directory already exists', 'Editorial' ) );
        }

        mkdir( $new_theme_path );

        // Make style.css
        ob_start();
        require dirname(__FILE__).'/editorial-custom-css.php';
        $css = ob_get_clean();
        file_put_contents( $new_theme_path.'/style.css', $css );

        // Copy screenshot
        $parent_theme_screenshot = $theme_root.'/'.$this_theme_name.'/screenshot.png';
        if ( file_exists( $parent_theme_screenshot ) ) {
            copy( $parent_theme_screenshot, $new_theme_path.'/screenshot.png' );
        } elseif (file_exists( $parent_theme_screenshot = $theme_root.'/'.$this_theme_template.'/screenshot.png' ) ) {
            copy( $parent_theme_screenshot, $new_theme_path.'/screenshot.png' );
        }

        $allowed_themes = get_site_option( 'allowedthemes' );
        $allowed_themes[ $child_theme_name ] = true;
        update_site_option( 'allowedthemes', $allowed_themes );

        switch_theme( $this_theme_template, $child_theme_name );

    }

    /**
     * Handle checkboxes
     *
     * @return void
     */
    private function _handleCheckboxes($checkboxes)
    {
        foreach ($checkboxes as $check)
        {
            if (!isset($_POST[$check]))
            {
                Editorial::setOption($check, false);
            }
        }
    }

    /**
     * Show cache notice
     *
     * @return void
     */
    public function cacheNotice()
    {
        $this->_showNotice(__('<strong>Cache folder is missing or not writable</strong>. Please make sure the cache folder, located in <code>/wp-content/cache</code> exists and is writable by the server.', 'Editorial')." ");
    }

    /**
     * Check if an update is available
     * -------------------------------
     * DISCLAMER: By changing any of the code in this method you are voiding the agreement
     * you have entered in with editorialtemplate.com and are liable for legal actions.
     * And further more you are stealing money from honest developers trying to make
     * a living with something awesome. Shame on you.
     *
     * @return void
     */
    private function _checkVersion($data)
    {
        // notices can be disabled
        Editorial::setOption('pirates', false);
        if ($data !== false)
        {
            if (!is_array($data) || !isset($data['valid']) || !$data['valid'])
            {
                //add_action('admin_notices', array($this, 'invalidNotice'));
                Editorial::setOption('pirates', true);
                return false;
            }
            // update available?
            if (is_array($data) && isset($data['new_version']) && EDITORIAL_VERSION != $data['new_version'])
            {
                if (!Editorial::getOption('disable-admin-notices')) {
                        add_action('admin_notices', array($this, 'updateNotice'));
                    }
                    return true;
            }
        }
    }

    public function checkUpdate()
    {
        //$url = parse_url( get_bloginfo('url') );

        //if( !$url['host'] == 'localhost') {
            add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update'));
            add_filter('themes_api', array($this, 'my_theme_api_call'), 10, 3);
        //}
    }

    public function check_for_update($checked_data)
    {
        global $wp_version;

        if(function_exists('wp_get_theme')){
          $theme_data = wp_get_theme(get_option('template'));
          $theme_version = $theme_data->Version;
        } else {
          $theme_data = get_theme_data( TEMPLATEPATH . '/style.css');
          $theme_version = $theme_data['Version'];
        }
        $theme_base = get_option('template');

        $request = array(
            'slug' => $theme_base,
            'version' => $theme_version
        );
        // Start checking for an update
        $send_for_check = array(
            'body' => array(
                'action' => 'theme_update',
                'request' => serialize($request),
                'api-key' => md5(get_bloginfo('url')),
                'blog-url' => get_bloginfo('url') //site_url()
            ),
            'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
        );
        $raw_response = wp_remote_post(EDITORIAL_UPDATE_API, $send_for_check);
        if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
            $response = unserialize($raw_response['body']);

        // Feed the update data into WP updater
        if (!empty($response))
        {
            $checked_data->response[$theme_base] = $response;
            //var_dump($response);
            if (! $this->_checkVersion($response))
            {
                return false;
            }
        }
        else
        {
            Editorial::setOption('pirates', false);
        }

        return $checked_data;

    }

    public function my_theme_api_call($def, $action, $args)
    {
        if(function_exists('wp_get_theme')){
          $theme_data = wp_get_theme(get_option('template'));
          $theme_version = $theme_data->Version;
        } else {
          $theme_data = get_theme_data( TEMPLATEPATH . '/style.css');
          $theme_version = $theme_data['Version'];
        }
        $theme_base = get_option('template');

        if ($args->slug != $theme_base)
            return false;

        // Get the current version

        $args->version = $theme_version;
        $request_string = prepare_request($action, $args);
        $request = wp_remote_post(EDITORIAL_UPDATE_API, $request_string);

        if (is_wp_error($request)) {
            $res = new WP_Error('themes_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>', 'Editorial'), $request->get_error_message());
        } else {
            $res = unserialize($request['body']);

            if ($res === false)
                $res = new WP_Error('themes_api_failed', __('An unknown error occurred', 'Editorial'), $request['body']);
        }

        return $res;

    }

    /**
     * Add notice that an update is available
     *
     * @return void
     */
    public function updateNotice()
    {
        if (Editorial::getOption('disable-admin-notices')) return;
        $msg = sprintf("<strong>Editorial theme update is available.</strong> You can update it through the <a href='%s/wp-admin/update-core.php'>Updates</a> section in the Admin", get_bloginfo('url'));
        $this->_showNotice(__($msg, 'Editorial'));
    }

    /**
     * Add notice that an update is available
     *
     * @return void
     */
    public function invalidNotice()
    {
        $this->_showNotice(__('<strong>This is a non-licenced copy of Editorial theme.</strong>. If you like our work please support it by purchasing a licence at <a href="http://editorialtemplate.com/">editorialtemplate.com</a>.', 'Editorial'));
    }

    /**
     * Show notice
     *
     * @return void
     */
    private function _showNotice($notice)
    {
        echo "<div class='updated fade'><p>".$notice."</p></div>";
    }

    /**
     * Display user in administration. Outputs a list item.
     *
     * @param  Object $user
     * @param  string $title
     * @param  bool   $checked
     * @return void
     */
    public static function displayUser($user, $title = '', $checked = true)
    {

        $gravatar = sprintf(
                        'http://www.gravatar.com/avatar/%s?&s=%d',
                        md5(strtolower(trim($user->user_email))),
                        20
                    );

        return sprintf('<li id="user_%1$d">
                    <span class="handle">handle</span>
                    <img src="%5$s" class="photo" width="20" height="20" />
                    <input type="checkbox" name="author[]" value="%1$d"%4$s />
                    <strong>%2$s</strong>
                    <input type="text" name="title[]" value="%3$s" placeholder="Role description" />
                </li>', $user->ID, $user->display_name, $title, ($checked ? ' checked="checked"' : ''), $gravatar);
    }

    /**
     * Generate a new editorial kit on typekit
     *
     * @return bool true on success, false on error
     */
    public function typekit()
    {
        // make sure we have token
        if (!Editorial::getOption('typekit-token') || strlen(Editorial::getOption('typekit-token')) < 20)
        {
            return false;
        }
        // check if kit already exists
        if (Editorial::getOption('typekit-kit'))
        {
            // fetch info about kit
            $this->_typekitCheckKit();
        }
        // create & publish new kit for domain with MinionPro
        else
        {
            $this->_typekitCreateKit();
        }
    }

    /**
     * Fetch typekit info
     *
     * @return bool
     */
    private function _typekitCheckKit()
    {
        list($code, $response) = $this->_typekitAPICall(sprintf('kits/%s/', Editorial::getOption('typekit-kit')));
        if ($code != 200)
        {
            // remove kit
            Editorial::setOption('typekit-kit', false);
        }
        else
        {
            $data = json_decode($response, true);
            if (is_array($data) && isset($data['kit']) && !isset($data['kit']['published']))
            {
                // publish kit
                $this->_typekitPublish();
            }
        }
    }

    /**
     * Create typekit kit with MinionPro
     *
     * @param  string $call API call path
     * @return void
     */
    private function _typekitCreateKit()
    {
        $params = array(
            'name' => sprintf('%s (Editorial)', get_bloginfo('name')),
            'domains' => sprintf('%s, 127.0.0.1', home_url()),
            'badge' => false,
            'families' => array(
                array(
                    //'id' => 'gkmg' // Droid Sans
                    'id'         => 'nljb', // Minion Pro
                    'subset'     => 'default',
                    'variations' => array('n4', 'i4', 'n6', 'i6'),
                ),
            ),
            'segmented_css_names' => false, // don't split css names
        );

        list($code, $response) = $this->_typekitAPICall('kits', $params);
        $data = json_decode($response, true);
        if ($code != 200)
        {
            $notice = __('<strong>Error!</strong> Typekit fonts were not enabled.', 'Editorial');
            if ($data && $data['errors'])
            {
                $notice .= ' '.implode(' ', $data['errors']);
            }
            $this->_showNotice($notice);
            return;
        }
        // success?
        if (is_array($data) && isset($data['kit']) && isset($data['kit']['id']))
        {
            // save id
            Editorial::setOption('typekit-kit', $data['kit']['id']);
            // publish
            $this->_typekitPublish();
        }
    }

    /**
     * Publish kit
     *
     * @return void
     */
    private function _typekitPublish()
    {
        list($code, $response) = $this->_typekitAPICall(sprintf('kits/%s/publish', Editorial::getOption('typekit-kit')), true);
        $data = json_decode($response, true);
        if ($code != 200)
        {
            $notice = __('<strong>Error!</strong> Typekit kit was created but not published.', 'Editorial');
            if ($data && $data['errors'])
            {
                $notice .= ' '.implode(' ', $data['errors']);
            }
            $this->_showNotice($notice);
            return;
        }
        else
        {
            $this->_showNotice(sprintf(__('<strong>Success!</strong> Typekit font has been created and is being published as we speak. Should take a couple of minutes to see the difference on your website so keep your pants on.', 'Editorial')));
        }
    }

    /**
     * Typekit API call
     *
     * @param  string $call
     * @param  Array $params Call params
     * @return array array of $code and $body
     */
    private function _typekitAPICall($call, $params = false)
    {
        $url = sprintf(
           'https://typekit.com/api/v1/json/%s',
           $call
        );

        // setup curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(sprintf('X-Typekit-Token:%s', Editorial::getOption('typekit-token'))));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // if we have params create post request
        if ($params)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            if (is_array($params) && count($params))
            {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }
            else
            {
                curl_setopt($ch, CURLOPT_POSTFIELDS, false);
            }
        }
        // execute request
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array($code, $result);
    }

    /**
     * Display pirate warning
     *
     * @return void
     */
    public function displayWarning()
    {
        if (Editorial::getOption('pirates'))
        {
            $response = wp_remote_get('http://editorialtemplate.com/pirates/message.html');
            if( !is_wp_error( $response ) )
            {
               echo $response['body'];
            }
        }
    }

}

// add admin capabilites
$Editorial = new Editorial_Admin();

//add_action( 'tgmpa_register', 'my_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
*/
function my_theme_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        array(
            'name' 		=> 'Facebook',
            'slug' 		=> 'facebook',
            'required' 	=> false,
        ),
        array(
            'name' 		=> 'Disqus Comment System',
            'slug' 		=> 'disqus-comment-system',
            'required' 	=> false,
        ),
        array(
            'name' 		=> 'Social',
            'slug' 		=> 'social',
            'required' 	=> false,
        ),
    );

    // Change this to your theme text domain, used for internationalising strings
    $theme_text_domain = 'Editorial';

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
        'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
        'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
        'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
        'menu'         		=> 'install-required-plugins', 	// Menu slug
        'has_notices'      	=> true,                       	// Show admin notices or not
        'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
        'message' 			=> '',							// Message to output right before the plugins table
        'strings'      		=> array(
            'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
            'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
            'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
            'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
            'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
            'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
            'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
            'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
            'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
            'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
            'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
            'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        ),
    );

    tgmpa( $plugins, $config );

}

?>