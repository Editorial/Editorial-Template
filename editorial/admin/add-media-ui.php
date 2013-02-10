<?php

global $post, $fields;

function editorial_attachment_fields( $form_fields, $post ) {
    unset( $form_fields['image-size'], $form_fields['align'], $form_fields['image_alt']
     /*,$form_fields['post_title'], $form_fields['post_excerpt'], $form_fields['post_content'],
            $form_fields['url'], $form_fields['menu_order']/*, $form_fields['image_url'] */);
    return $form_fields;
}

function editorial_image_tabs ($strings, $post)
{
    global $galleryMode, $post;
    $post_id = $post->ID;
    $galleryMode = true;
    $meta_key = 'editorial_post_gallery_mode';
    $galleryMode = get_post_meta($post_id, $meta_key, true);

    if (isset($galleryMode) && $galleryMode === '0')
        $galleryMode = false;
    if ($galleryMode === "")
        $galleryMode = 1;
    add_filter('media_upload_tabs', 'remove_media_library_tab');
    add_action('admin_enqueue_scripts', 'load_js_scripts', 5);
    add_action('admin_footer-post-new.php', 'restart_tabs', 5);
    add_action('admin_footer-post.php', 'restart_tabs', 5);

    // print_r($strings);
    $strings['insertGallery'] = __('Save gallery');
    if ($galleryMode) {
      //  unset($strings['insertMediaTitle']);
    } else {
       // unset($strings['createGalleryTitle']);
    }
    unset($strings['editGalleryTitle']);
    unset($strings['createGalleryTitle']);
    unset($strings['insertFromUrlTitle']);
 //   $strings['createGalleryTitle'] = '';
    $strings['addToGalleryTitle'] = __('Media gallery', 'editorial');
    $strings['addToGallery'] = __('Save gallery', 'editorial');
    $strings['customMenuTitle'] = __('Custom Menu Title', 'custom');

    $strings['customButton'] = __('Custom Button', 'custom');
    return $strings;
}

function editorial_attachment_fields_to_edit( $fields, $post ) {
    $file = wp_get_attachment_metadata($post->ID, true);
    unset($fields['buttons'] );
    if (isset($file['embed_type']) && $file['embed_type'] == 'video') {
        $fields['media_url'] = array(
        'label' => 'URL',
        'input' => 'html',
        'meta' => 'media_url',
        'value' => $file['embed_type'],
        'html' => $file['_wp_attachment_url'],
        'show_in_edit' => false,
        'show_in_modal' => true,
        );
        $fields['media_type'] = array(
        'input' => 'hidden',
        'value' => $file['embed_type'],
        'show_in_edit' => true,
        'show_in_modal' => true,
        );
    }
    return $fields;
}
function remove_media_library_tab($tabs) {

    unset($tabs['library']);
    return $tabs;
}
function fetch_video(){
           global $post;
        if (isset($_POST['url'])) {
        $_POST['url'] = str_replace('https://', 'http://', $_POST['url']);
        require_once( ABSPATH . WPINC . '/class-oembed.php' );


        global $current_user;
        get_currentuserinfo();
        $logged_in_user = $current_user->ID;

        if (isset( $_POST['post_ID'])) $postId = (int)$_POST['post_ID'];
        else $postId = $post->ID;

        $url = $_POST['url'];
        if ($_POST['type'] == 'image') {
                $data['url'] = $url;
                $data['type'] = 'image';
        } else {

            $emBed = new WP_oEmbed();

            $provider = $emBed->discover($url);
            $data = $emBed->fetch($provider, $url);
        }
        if ($data) {
            $dataArray = (array)$data;
            $type = (isset($dataArray['type'])?$dataArray['type']:'video');
            //print_r($data);exit();
            if (!$data OR ($type == 'video' &&  (strtolower($data->provider_name) != 'youtube' && strtolower($data->provider_name) != 'vimeo')))	{
                $erroData['error'] = 'can not fetch';
                $erroData['success'] = false;
                echo json_encode($erroData);
                exit();
            }
            $dataArray['type'] = 'video';

            if ($type == 'video' && isset($dataArray['thumbnail_url'])){
                    $file = $dataArray['thumbnail_url'];
            }else {
                $file = $dataArray['url'];
            }
            $upload = wp_upload_dir();
            if ( $upload['error'] !== false ) return json_encode($upload);
            $filetype = wp_check_filetype(basename( $file));
            $ext = $filetype['ext'];
            $filename = wp_unique_filename( $upload['path'],  sanitize_title_with_dashes(basename( $url)).'.'.$ext );
            $new_file = $upload['path'] . "/$filename";

            if ( ! wp_mkdir_p( dirname( $new_file ) ) ) {
                if ( 0 === strpos( $upload['basedir'], ABSPATH ) )
                    $error_path = str_replace( ABSPATH, '', $upload['basedir'] ) . $upload['subdir'];
                else
                  $error_path = basename( $upload['basedir'] ) . $upload['subdir'];

                $message = sprintf( __( 'Unable to create directory %s. Is its parent directory writable by the server?' ), $error_path );
                return array( 'error' => $message );
            }
           // print_r($dataArray);

                if (copy($file, $new_file) ) {
                    $attachment = array(
                            'post_mime_type' => $filetype['type'],
                            'post_title' => $dataArray['title'],
                            'post_name' => $dataArray['title'],
                            'post_content' =>$url,
                            'post_excerpt' => isset($dataArray['description'])?$dataArray['description']:'',
                            'post_author' => $logged_in_user,
                            'post_status' => 'inherit',
                            'post_type' => 'attachment',
                            'post_parent' => $postId,
                            'guid' => $upload['url'].'/' .$filename
                    );
                  //  print_r($upload);
                  /*  $attachment_id = wp_insert_post( $attachment );*/
                    $attachment_id = wp_insert_attachment($attachment, $upload['subdir'].'/'.$filename, $postId);


                    $attach_data = wp_generate_attachment_metadata( $attachment_id, $new_file );
                    $attach_data['_wp_attachment_url'] = $url;
                    $attach_data['provider_name'] = $data->provider_name;
                    $attach_data['embed_type'] = $type;
                    if ( !is_wp_error($id) ) {
                        //wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
                           // update_post_meta($postId, '_wp_attachment_url', esc_url_raw($attachment['url']) );
                        wp_update_attachment_metadata( $attachment_id,  $attach_data );
                     }
            } else {
                return array( 'success'=>false, 'error' => sprintf( __( 'Could not write file %s' ), $new_file ) );
            }

            clearstatcache();

            // Set correct file permissions
            $stat = @ stat( dirname( $new_file ) );
            $perms = $stat['mode'] & 0007777;
            $perms = $perms & 0000666;
            @ chmod( $new_file, $perms );
            clearstatcache();

            // Compute the URL
            $url = $upload['url'] . "/$filename";

            $fileArray= array( 'file' => $new_file, 'url' => $url, 'error' => false );
            $attachment['id'] = $attachment_id;
            $dataArray['url'] = $url;
            $dataArray['file'] = $new_file;
            $attachment['data'] = $dataArray;
            echo json_encode($attachment);
        } else {
            $data['error'] = 'can not fetch';
            $data['success'] = false;
            echo json_encode($data);
        }
        exit();
    }
}
function restart_tabs()
{
  ?>
<script type="text/javascript">
    var templateDir = "<?php bloginfo('template_directory') ?>/admin";
    var templateImgDir = "<?php bloginfo('template_directory') ?>/images";
    var wpDir = "<?php bloginfo('wpurl');
     ?>";
    </script>
<?php
}
function load_js_scripts()
{
    wp_register_script( 'media-view-editorial', get_template_directory_uri() . '/admin/js/media-view-editorial.js', array( 'jquery' ) );
    wp_enqueue_script( 'media-view-editorial');
    wp_register_script( 'switchControl', get_template_directory_uri() . '/admin/js/jquery.switch.js', array( 'jquery' ) );
    wp_enqueue_script( 'switchControl');
    wp_register_style( 'admin-css-editorial', get_template_directory_uri() . '/admin/css/admin.css');
    wp_enqueue_style( 'admin-css-editorial');

}

function editorial_pre_submit_validation()
{
    // simple Security check
    check_ajax_referer('pre_publish_validation', 'security');
    // convert the string of data received to an array
    // from http://wordpress.stackexchange.com/a/26536/10406
    parse_str($_POST['form_data'], $vars);
    $content = $vars['content'];
    // check that are actually trying to publish a post
    if (isset($_POST['editorial-post-gallery-mode']) && $_POST['editorial-post-gallery-mode'] == '1') {
        if ($vars['post_status'] == 'publish' || (isset($vars['original_publish']) &&
         in_array($vars['original_publish'], array('Publish', 'Schedule', 'Update')))) {
            if (stristr($content, '<img')) {
                echo 'false';
                die();
            } else
                if (stristr($content, '<iframe')) {
                    echo 'false';
                    die();
                } else
                    if (stristr($content, '<video')) {
                        echo 'false';
                        die();
                    } else
                        if (stristr($content, '<audio')) {
                            echo 'false';
                            die();
                        } else {
                            echo 'true';
                            die();
                            // require_once( ABSPATH . WPINC .
                        // '/class-oembed.php' );
                            // $emBed = new WP_oEmbed();
                            // $provider = $emBed->discover($url);
                        }
        }
    }
    // everything ok, allow submission
    echo 'true';
    die();
}
?>
<?php

require_once locate_template('/admin/media-template.php');
add_action( 'admin_footer', 'wp_print_editorial_media_templates' );
add_action( 'wp_footer', 'wp_editorial_print_media_templates' );