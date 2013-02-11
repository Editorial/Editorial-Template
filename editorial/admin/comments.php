<?php

$tmg = new TGM_Plugin_Activation;

$valid_plugins = array(
    'social',
    'disqus',
    'facebook',
);

if (isset($_GET['plugin']) && in_array($_GET['plugin'], $valid_plugins))
{
    // install plugin
    $tmg->do_plugin_install();
}
else
{
    if (isset($_GET['activate']) && in_array($_GET['activate'], $valid_plugins))
    {
        $type = $_GET['activate'];
        // by default activate disqus
        $activate = array(
            'required' 	=> false,
            'force_activation' => true,
        );
        // activate disqus
        if ($type == 'disqus' || $type == 'disqus-comment-system')
        {
            $activate['name'] = 'Disqus Comment System';
            $activate['slug'] = 'disqus-comment-system';
        }
        // activate facebook
        if ($type == 'facebook')
        {
            // if plugin is already active just activate comments later on
            if (!is_plugin_active('facebook/facebook.php'))
            {
                $activate['name'] = 'Facebook';
                $activate['slug'] = 'facebook';
            }
        }
        // activate social
        if ($type == 'social')
        {
            $activate['name'] = 'Social';
            $activate['slug'] = 'social';
        }

        // activate if you got em
        if (count($activate) > 2)
        {
            $tmg->plugins = array($activate);
            $tmg->force_activation();

            // if we activate via tmg the plugin activatin hook does not get called
            // so call it by hand (it deactivates other plugins)
            Editorial_Admin::pluginActivateHook($activate['slug']);
        }

        // if plugin is social force default theme comments
        if ($type == 'social' && !get_option('social_use_standard_comments'))
        {
            update_option('social_use_standard_comments', 1);
        }

        // if plugin activated is facebook enable facebook comments
        if ($type == 'facebook')
        {
            Editorial_Admin::activateFacebookComments();
            Editorial_Admin::pluginActivateHook('facebook');
        }

        if ($type == 'disqus') $activate['name'] = 'Disqus';
        if ($type == 'facebook') $activate['name'] = 'Facebook';
        printf(
            '<div class="updated fade"><p>Activated %s comments.</p></div>',
            $activate['name']
        );
    }
    else if (isset($_GET['deactivate']) && in_array($_GET['deactivate'], $valid_plugins))
    {
        // remove comment setting
        Editorial::setOption('comment-plugin', false);
        $type = $_GET['deactivate'];
        $deactivate = array(
            'name' 		=> 'Disqus Comment System',
            'slug' 		=> 'disqus-comment-system',
            'force_deactivation' => true,
        );
        // activate facebook
        if ($type == 'facebook')
        {
            $deactivate['name'] = 'Facebook';
            $deactivate['slug'] = 'facebook';
        }
        // activate social
        if ($type == 'social')
        {
            $deactivate['name'] = 'Social';
            $deactivate['slug'] = 'social';
        }

        if ($type == 'facebook')
        {
            // only deactivate comments
            Editorial_Admin::deactivateFacebookComments();
        }
        else
        {
            // deactivate
            $tmg->plugins = array($deactivate);
            $tmg->force_deactivation();
        }

        printf(
            '<div class="updated fade"><p>%s comments deactivated.</p></div>',
            $deactivate['name']
        );
    }

    if (isset($_GET['installed']))
    {
        echo '<div class="updated fade"><p>Plugin installed and activated.</p></div>';
    }

    // forces a specific provider to open
    if (isset($_GET['open']) && in_array($_GET['open'], $valid_plugins))
    {
        $selected = $_GET['open'];
    }
    else
    {
        $system = Editorial::getCommentSystem();
        $selected = 'social';
        if ($system == Editorial::COMMENT_DISQUS)
        {
            $selected = 'disqus';
        }
        else if ($system == Editorial::COMMENT_FACEBOOK)
        {
            $selected = 'facebook';
        }
    }

    // check if more than one comments are open
    $enabled = Editorial::getEnabledCommentSystems();
    if (count($enabled) > 1)
    {
        $last = array_pop($enabled);
        echo '<div class="error fade"><p>You are using more than one comment system. Please choose between '.implode(', ', $enabled).' and '.$last.'.</p></div>';
    }
?>

<h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Comments', 'Editorial'); ?></h2>

<div class="poststuff">
    <!-- #post-body .metabox-holder goes here -->
<div id="post-body" class="metabox-holder columns-2">
    <!-- meta box containers here -->


<div id="postbox-container" class="postbox-container">
<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div id="comment_provider">
        <h2>Select your comments system</h2>
        <select>
            <option value="wp"<?php echo (!$selected || $selected == 'social') ? ' selected="selected"' : '' ?>>WordPress</option>
            <option value="disqus"<?php echo $selected == 'disqus' ? ' selected="selected"' : '' ?>>Disqus</option>
            <option value="fb"<?php echo $selected == 'facebook' ? ' selected="selected"' : '' ?>>Facebook</option>
        </select>
        <div id="comments_wp" class="provider<?php echo (!$selected || $selected == 'social') ? ' open' : '' ?>">
            <form action="admin.php?page=editorial-comments&system=wp" method="post" enctype="multipart/form-data">
                <div class="postbox">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Social integration', 'Editorial'); ?></span></h3>
                    <div class="inside">
                        <div class="table table_content">
                            <p>Brought to you by <a href="http://mailchimp.com/" rel="nofollow">MailChimp</a>, <a href="http://mailchimp.com/social-plugin-for-wordpress/" rel="nofollow">Social</a> is a lightweight plugin that handles a lot of the heavy lifting of making your blog seamlessly integrate with social networking sites <a href="http://twitter.com/" rel="nofollow">Twitter</a> and <a href="http://facebook.com/" rel="nofollow">Facebook</a>.</p>
                            <p>When publishing to Facebook and Twitter, the discussion is likely to continue there. Through Social, we can aggregate the various mentions, retweets, @replies, comments and responses and republish them as WordPress comments.</p>
                            <?php

                            $plugins = get_plugins();

                            $social = 'social/social.php';
                            if (!array_key_exists($social, $plugins))
                            {
                                printf(
                                    '<p><a class="button-primary" href="%sadmin.php?page=editorial-comments&plugin=social&plugin_name=Social&plugin_source=repo&tgmpa-install=install-plugin&_wpnonce=%s">Install plugin</a>',
                                    get_admin_url(),
                                    wp_create_nonce('tgmpa-install')
                                );
                            }
                            else
                            {
                                if (is_plugin_inactive($social))
                                {
                                    printf(
                                        '<p><a class="button-primary" href="%sadmin.php?page=editorial-comments&activate=social&open=social">Activate plugin</a></p>',
                                        get_admin_url()
                                    );
                                }
                                else if (is_plugin_active($social))
                                {
                                    printf(
                                        '<p>
                                            <a class="button-primary" href="%1$soptions-general.php?page=social.php">Plugin settings</a>
                                            <a class="button-primary" href="%1$sadmin.php?page=editorial-comments&deactivate=social">Deactivate plugin</a>
                                        </p>',
                                        get_admin_url()
                                    );
                                }
                            }

                            ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="postbox">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Comments moderation', 'Editorial'); ?></span></h3>
                    <div class="inside">
                        <div class="table table_content">
                            <label><?php _e('Enable users to moderate inappropriate comments by voting', 'Editorial') ?> <input type="checkbox" name="karma"<?php echo !Editorial::getOption('karma') ? '' : ' checked="checked"'; ?> /></label><br />
                            <input type="text" name="karma-treshold" value="<?php echo Editorial::getOption('karma-treshold'); ?>" />
                            <p class="note karma"><?php _e('Number of votes required to hide an inappropriate comment', 'Editorial'); ?></p>
                            <div class="clear"></div>
                            <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'Editorial'); ?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="comments_disqus" class="provider<?php echo $selected == 'disqus' ? ' open' : '' ?>">
            <div class="postbox">
                <div class="handlediv" title="Click to toggle"><br></div>
                <h3 class="hndle"><span><?php _e('Disqus integration', 'Editorial'); ?></span></h3>
                <div class="inside">
                    <div class="table table_content">
                        <p><img src="<?php echo get_bloginfo('template_directory'); ?>/images/admin/disqus.png" /> Disqus, pronounced "discuss", is a service and tool for web comments and discussions. Disqus makes commenting easier and more interactive, while connecting websites and commenters across a thriving discussion community.</p>
                        <p>The Disqus comment system replaces your WordPress comment system with your comments hosted and powered by Disqus.</p>
                        <?php

                        // check if plugin is installed
                        $disqus = 'disqus-comment-system/disqus.php';

                        if (!array_key_exists($disqus, $plugins))
                        {
                            printf(
                                '<p><a class="button-primary" href="%sadmin.php?page=editorial-comments&plugin=disqus-comment-system&plugin_name=Disqus+Comment+System&plugin_source=repo&tgmpa-install=install-plugin&_wpnonce=%s">Install plugin</a>',
                                get_admin_url(),
                                wp_create_nonce('tgmpa-install')
                            );
                        }
                        else
                        {
                            if (is_plugin_inactive($disqus))
                            {
                                printf(
                                    '<p><a class="button-primary" href="%sadmin.php?page=editorial-comments&activate=disqus&open=disqus">Activate plugin</a></p>',
                                    get_admin_url()
                                );
                            }
                            else if (is_plugin_active($disqus))
                            {
                                printf(
                                    '<p>
                                        <a class="button-primary" href="%1$sedit-comments.php?page=disqus#adv">Plugin settings</a>
                                        <a class="button-primary" href="%1$sadmin.php?page=editorial-comments&deactivate=disqus">Deactivate plugin</a>
                                    </p>',
                                    get_admin_url()
                                );
                            }
                        }

                        ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="comments_fb" class="provider<?php echo $selected == 'facebook' ? ' open' : '' ?>">
            <div class="postbox">
                <div class="handlediv" title="Click to toggle"><br></div>
                <h3 class="hndle"><span><?php _e('Facebook integration', 'Editorial'); ?></span></h3>
                <div class="inside">
                    <div class="table table_content">
                        <p><img src="<?php echo get_bloginfo('template_directory'); ?>/images/admin/facebook.png" /> The Facebook plugin for WordPress adds Facebook social plugins to your WordPress site. Associate your WordPress site with a free Facebook application identifier to enable advanced features such as automatically sharing new posts to an author's Facebook timeline or your site's Facebook page. This plugin is developed by Facebook with extra support for popular plugins and themes.</p>
                        <?php

                        $fb = 'facebook/facebook.php';
                        if (!array_key_exists($fb, $plugins))
                        {
                            printf(
                                '<p><a class="button-primary" href="%sadmin.php?page=editorial-comments&plugin=facebook&plugin_name=Facebook&plugin_source=repo&tgmpa-install=install-plugin&_wpnonce=%s">Install plugin</a>.',
                                get_admin_url(),
                                wp_create_nonce('tgmpa-install')
                            );
                        }
                        else
                        {
                            if (Editorial::areFacebookCommentsActive())
                            {
                                printf(
                                    '<p>
                                        <a class="button-primary" href="%1$sadmin.php?page=facebook-application-settings">Plugin settings</a>
                                        <a class="button-primary" href="%1$sadmin.php?page=editorial-comments&deactivate=facebook">Deactivate comments</a>
                                    </p>',
                                    get_admin_url()
                                );
                            }
                            else
                            {
                                printf(
                                    '<p><a class="button-primary" href="%sadmin.php?page=editorial-comments&activate=facebook&open=facebook">Activate comments</a></p>',
                                    get_admin_url()
                                );
                            }
                        }

                        ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- FAQ BOX -->
<?php include 'faq_look.php'; ?>

</div>
</div>

<?php } ?>
