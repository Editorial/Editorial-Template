
<?php
    add_action('admin_init', 'editor_admin_init');
    add_action('admin_head', 'editor_admin_head');

    function editor_admin_init() {
      wp_enqueue_script('word-count');
      wp_enqueue_script('post');
      wp_enqueue_script('editor');
    }

    function editor_admin_head() {
      wp_tiny_mce();
    }
?>


    <h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Masthead', 'Editorial'); ?></h2>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function() {
        // set up sorting
        jQuery("#authors").sortable({
            handle: '.handle',
        });
        // if checkbox is disabled disable the input field
        jQuery('#authors input[type="checkbox"]').click(function() {
            jQuery(this).parent().find('input[type="text"]').attr('disabled', !jQuery(this).attr('checked'));
        })
    });
    </script>

<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="postbox-container" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <form action="" method="post">
                    <?php wp_nonce_field( 'some-action-nonce' );
                /* Used to save closed meta boxes and their order */
                wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
                wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>

                 <div class="postbox " style="display: block; ">
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <h3 class="hndle"><span><?php _e('General', 'Editorial'); ?></span></h3>
                        <div class="inside">
                          <div class="table table_content">
                              <label><?php _e('Enable Masthead', 'Editorial'); ?> <input type="checkbox" name="colophon-enabled"<?php echo !Editorial::getOption('colophon-enabled') ? '' : ' checked="checked"'; ?> /></label>
                          </div>
                      </div>
                  </div>

                <div class="postbox " style="display: block; ">
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <h3 class="hndle"><span><?php _e('Team members', 'Editorial'); ?></span></h3>
                        <div class="inside">
                          <div class="table table_content">
                              <?php

                                        $users = get_users(array(
                                            'who' => 'author',
                                        ));
                                        if (count($users))
                                        {
                                            echo '<ul id="authors">';
                                            // load saved order and
                                            $authors = Editorial::getOption('authors');
                                            $alreadyShown = array();
                                            if (is_array($authors) && count($authors))
                                            {
                                                foreach ($authors as $id => $title)
                                                {
                                                    $data = get_userdata($id);
                                                    if (!$data)
                                                    {
                                                        // user data not loaded
                                                        continue;
                                                    }
                                                    echo Editorial_Admin::displayUser($data, $title);
                                                    $alreadyShown[] = $id;
                                                }
                                            }
                                            foreach ($users as $user)
                                            {
                                                if (in_array($user->ID, $alreadyShown))
                                                {
                                                    // skip already shown users
                                                    continue;
                                                }
                                                echo Editorial_Admin::displayUser($user, '', !(bool)$authors);
                                            }
                                            echo '</ul>';
                                        }

                                        ?>
                          </div>
                      </div>
                  </div>


                <div class="postbox " style="display: block; ">
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <h3 class="hndle"><span><?php _e('About', 'Editorial'); ?></span></h3>
                        <div class="inside">
                          <div class="table table_content">
                              <?php
                                  $colophon_page = get_page_by_title('colophon');
                                  $colophon_text =  $colophon_page->post_content;//Editorial::getOption('colophon-text');
                                     the_editor($colophon_text, "content_for_colophon", "", false);
                                    ?>
                          </div>
                      </div>
                  </div>

                <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'editorial'); ?>">
                    </p>
              </form>
            </div>
        </div>
    </div>
</div>

