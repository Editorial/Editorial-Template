    <h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Sharing', 'Editorial'); ?></h2>
    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th><?php _e('Twitter share', 'Editorial'); ?></th>
                <td>
                    <label><?php _e('Enable twitter share', 'Editorial'); ?> <input type="checkbox" name="twitter-share"<?php echo !Editorial::getOption('twitter-share') ? '' : ' checked="checked"'; ?> /></label><br />
                    <input type="text" name="twitter-account" value="<?php echo Editorial::getOption('twitter-account'); ?>" placeholder="<?php _e('Your twitter account', 'Editorial'); ?>" /><br />
                    <input type="text" name="twitter-related" value="<?php echo Editorial::getOption('twitter-related'); ?>" placeholder="<?php _e('Related account', 'Editorial'); ?>" /><br />
                    <input type="text" name="twitter-related-desc" value="<?php echo Editorial::getOption('twitter-related-desc'); ?>" placeholder="<?php _e('Related account description', 'Editorial'); ?>" />
                    <p class="note"><?php _e('Twitter share is visible on article page.', 'Editorial'); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Facebook share', 'Editorial'); ?></th>
                <td>
                    <label><?php _e('Enable facebook share', 'Editorial'); ?> <input type="checkbox" name="facebook-share"<?php echo !Editorial::getOption('facebook-share') ? '' : ' checked="checked"'; ?> /></label>
                    <p class="note"><?php _e('Facebook share is visible on article page.', 'Editorial'); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Google share', 'Editorial'); ?></th>
                <td>
                    <label><?php _e('Enable google share', 'Editorial'); ?> <input type="checkbox" name="google-share"<?php echo !Editorial::getOption('google-share') ? '' : ' checked="checked"'; ?> /></label>
                    <p class="note"><?php _e('Google share is visible on article page.', 'Editorial'); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Readability', 'Editorial'); ?></th>
                <td>
                    <label><?php _e('Readability', 'Editorial'); ?> <input type="checkbox" name="readability-share"<?php echo !Editorial::getOption('readability-share') ? '' : ' checked="checked"'; ?> /></label>
                    <p class="note"><?php _e('Readability widget is visible on article page.', 'Editorial'); ?></p>
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'Editorial'); ?>"></p>
    </form>