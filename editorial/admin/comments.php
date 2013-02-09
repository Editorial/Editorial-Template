<?php

$tmg = new TGM_Plugin_Activation;

$valid_plugins = array(
    'social',
    'disqus',
    'facebook',
);

if (isset($_GET['plugin']) && in_array($_GET['plugin'], $valid_plugins))
{
    // save which plugin we are installing
    Editorial::setOption('comment-plugin', $_GET['plugin']);
    $tmg->do_plugin_install();
}
else
{
    if (isset($_GET['activate']) && in_array($_GET['activate'], $valid_plugins))
    {
        // save which plugin we are activating
        Editorial::setOption('comment-plugin', $_GET['plugin']);
        $type = $_GET['activate'];
        // by default activate disqus
        $activate = array(
            'name' 		=> 'Disqus Comment System',
            'slug' 		=> 'disqus-comment-system',
            'required' 	=> false,
            'force_activation' => true,
        );
        // activate facebook
        if ($type == 'facebook')
        {
            $activate['name'] = 'Facebook';
            $activate['slug'] = 'facebook';
        }
        // activate social
        if ($type == 'social')
        {
            $activate['name'] = 'Social';
            $activate['slug'] = 'social';
        }
        $tmg->plugins = array($activate);
        $tmg->force_activation();

        // if plugin is social force default theme comments
        if ($type == 'social' && !get_option('social_use_standard_comments'))
        {
            update_option('social_use_standard_comments', 1);
        }

        printf(
            '<div class="updated fade"><p>Plugin %s activated.</p></div>',
            $activate['name']
        );
    }

    if (isset($_GET['installed']))
    {
        echo '<div class="updated fade"><p>Plugin installed and activated.</p></div>';
    }

    $selected = Editorial::getOption('comment-plugin');
    if (!$selected)
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
                            <p>Brought to you by Mailchimp, Social Plugin for Wordpress is a perfect companion to the Editorial theme. Integrate WordPress with Twitter and Facebook, so you can collect everything people are saying about your blog in one place.</p>
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
                                        '<p><a class="button-primary" href="%sadmin.php?page=editorial-comments&activate=social">Activate plugin</a></p>',
                                        get_admin_url()
                                    );
                                }
                                else if (is_plugin_active($social))
                                {
                                    printf(
                                        '<p><a class="button-primary" href="%soptions-general.php?page=social.php">Plugin settings</a></p>',
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
                                    '<p><a class="button-primary" href="%sadmin.php?page=editorial-comments&activate=discus-comment-system">Activate plugin</a></p>',
                                    get_admin_url()
                                );
                            }
                            else if (is_plugin_active($disqus))
                            {
                                printf(
                                    '<p><a class="button-primary" href="%sedit-comments.php?page=disqus#adv">Plugin settings</a></p>',
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
                        <p>Make your WordPress site social in a couple of clicks, powered by Facebook.</p>
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
                            if (is_plugin_inactive($fb))
                            {
                                printf(
                                    '<p><a class="button-primary" href="%sadmin.php?page=editorial-comments&activate=facebook">Activate plugin</a></p>',
                                    get_admin_url()
                                );
                            }
                            else if (is_plugin_active($fb))
                            {
                                printf(
                                    '<p><a class="button-primary" href="%sadmin.php?page=facebook-application-settings">Plugin settings</a></p>',
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
