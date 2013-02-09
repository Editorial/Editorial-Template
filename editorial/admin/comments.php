<h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Comments', 'Editorial'); ?></h2>

<div class="poststuff">
    <div id="post-body" class="metabox-holder columns-2">

        <form action="admin.php?page=editorial-comments" method="post" enctype="multipart/form-data">
            <div class="postbox " style="display: block; ">
                <div class="handlediv" title="Click to toggle"><br></div>
                <h3 class="hndle"><span><?php _e('Comments moderation', 'Editorial'); ?></span></h3>
                    <div class="inside">
                    <div class="table table_content">

                        <label><?php _e('Enable users to moderate inappropriate comments by voting', 'Editorial') ?> <input type="checkbox" name="karma"<?php echo !Editorial::getOption('karma') ? '' : ' checked="checked"'; ?> /></label><br />
                  <input type="text" name="karma-treshold" value="<?php echo Editorial::getOption('karma-treshold'); ?>" />
                  <p class="note karma"><?php _e('Number of votes required to hide an inappropriate comment', 'Editorial'); ?></p>
                <div class="clear"></div>
                    </div>
                </div>
            </div>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'Editorial'); ?>"></p>
        </form>
    </div>
</div>