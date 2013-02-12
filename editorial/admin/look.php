    <h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Look &amp; Feel', 'Editorial'); ?></h2>

<div class="poststuff">
    <!-- #post-body .metabox-holder goes here -->
<div id="post-body" class="metabox-holder columns-2">
    <!-- meta box containers here -->


<div id="postbox-container" class="postbox-container">
<div id="normal-sortables" class="meta-box-sortables ui-sortable">

<form action="admin.php?page=editorial" method="post" enctype="multipart/form-data">

    <?php wp_nonce_field( 'some-action-nonce' );
    /* Used to save closed meta boxes and their order */
    wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
    wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>

    <div class="postbox " style="display: block; ">
        <div class="handlediv" title="Click to toggle"><br></div>
        <h3 class="hndle"><span><?php _e('Logo & Icons', 'Editorial'); ?></span></h3>
            <div class="inside">
              <div class="table table_content">

                  <table >
                            <tr>
                            <td>
                                <fieldset>
                                    <p class="logos"><img src="<?php echo Editorial::getOption('logo-big'); ?>" alt="Big logo" /></p>
                                    <input type="file" name="logo-image[logo-big]"/>
                                    <p class="note"><?php _e('Big logo is displayed on first page only. Recommended dimension 356x70px', 'Editorial'); ?></p>

                                    <p class="logos"><img src="<?php echo  Editorial::getOption('logo-small'); ?>" alt="Small logo" /></p>
                                    <input type="file" name="logo-image[logo-small]"/>
                                    <p class="note"><?php _e('Small logo is displayd on all subpages. Reommended dimension 200x40px', 'Editorial'); ?></p>

                                    <p class="logos"><img src="<?php echo  Editorial::getOption('logo-gallery'); ?>" alt="Gallery logo" class="gallery" /></p>
                                    <input type="file" name="logo-image[logo-gallery]"/>
                                    <p class="note"><?php _e('Gallery logo is displayed in mobile version of the gallery.<br/>Recommended dimension 131x17, prepared for black background.', 'Editorial'); ?></p>

                                    <p class="logos"><img src="<?php echo Editorial::getOption('touch-icon'); ?>" alt="Touch icon" width="60px" /></p>
                                    <input type="file" name="logo-image[touch-icon]"/>
                                    <p class="note"><?php _e('Touch icon is displayed if user saves the site to their homescreen (iPhone).', 'Editorial'); ?></p>

                                    <p class="logos"><img src="<?php echo  Editorial::getOption('favicon'); ?>" alt="Favicon"/></p>
                                    <input type="file" name="logo-image[favicon]"/>
                                    <p class="note"><?php _e('Site favicon.', 'Editorial'); ?></p>
                                </fieldset>
                            </td>
                        </tr>
                    </table>

              </div>
          </div>
    </div>



    <div class="postbox " style="display: block; ">
        <div class="handlediv" title="Click to toggle"><br></div>
        <h3 class="hndle"><span><?php _e('Typekit settings', 'Editorial'); ?></span></h3>
            <div class="inside">
              <div class="table table_content">

                  <fieldset>
            <label><?php _e('Typekit API Token', 'Editorial'); ?><br /><input type="text" name="typekit-token" value="<?php echo !Editorial::getOption('typekit-token') ? '' : Editorial::getOption('typekit-token'); ?>" placeholder="Enter Typekit API Token" /></label>
            <?php

            if (Editorial::getOption('typekit-token') && !Editorial::getOption('typekit-kit'))
            {
                // kit create failed
                echo '<p class="note">'.__('The Typekit font creation did not work. <a href="?page=editorial&typekit">Try again?</a>', 'Editorial').'</p>';
            }

            ?>
            <p class="note"><?php _e('You will need a <a href="http://typekit.com" target="_blank">typekit</a> account to enable custom font for Editorial template. <br />But no worries, we will handle that for you. Just head over to your Typekit account and <a href="https://typekit.com/account/tokens">generate one</a>. <br/>Editorial template uses font MinionPro which is available on Typekit for <em>$24.99/year</em>.', 'Editorial'); ?></p>
          </fieldset>

              </div>
          </div>
  </div>




  <div class="postbox " style="display: block; ">
        <div class="handlediv" title="Click to toggle"><br></div>
        <h3 class="hndle"><span><?php _e('Black &amp; White featured images', 'Editorial'); ?></span></h3>
            <div class="inside">
              <div class="table table_content">

                  <label><?php _e('Apply black & white effect to featured images') ?> <input type="checkbox" name="black-and-white"<?php echo !Editorial::getOption('black-and-white') ? '' : ' checked="checked"'; ?> /></label>
          <p class="note"><?php _e('The effect will appear on the home page and article lists, but not within article pages', 'Editorial'); ?></p>

              </div>
          </div>
  </div>


  <div class="postbox " style="display: block; ">
        <div class="handlediv" title="Click to toggle"><br></div>
        <h3 class="hndle"><span><?php _e('Copyright', 'Editorial'); ?></span></h3>
            <div class="inside">
              <div class="table table_content">

                  <input type="text" name="copyright" value="<?php echo Editorial::getOption('copyright'); ?>" placeholder="<?php _e('Copyright', 'Editorial'); ?>" />
          <p class="note"><?php _e('Copyright is visible in site footer.', 'Editorial'); ?></p>

              </div>
          </div>
  </div>


   <div class="postbox " style="display: block; ">
        <div class="handlediv" title="Click to toggle"><br></div>
        <h3 class="hndle"><span><?php _e('Theme notifications', 'Editorial'); ?></span></h3>
            <div class="inside">
              <div class="table table_content">

                  <label><?php _e('Disable wordpress notifications', 'Editorial') ?> <input type="checkbox" name="disable-admin-notices"<?php echo !Editorial::getOption('disable-admin-notices') ? '' : ' checked="checked"'; ?> /></label>
          <p class="note"><?php _e('If you disable wordpress notifications you will not see any typekit or theme update notifications.', 'Editorial'); ?></p>

              </div>
          </div>
  </div>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'Editorial'); ?>"></p>
</form>

</div>
</div>


<!-- FAQ BOX -->
<?php $faqGroup = 0; include 'faq.php'; ?>

</div>
</div>