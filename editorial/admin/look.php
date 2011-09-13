    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th><?php _e('Theme logo image', 'Editorial'); ?></th>
                <td>
                    <fieldset>
                        <?php

                        $logo_big     = !Editorial::getOption('logo-big') ? 'http://www.placeholder-image.com/image/356x70' : Editorial::getOption('logo-big');
                        $logo_small   = !Editorial::getOption('logo-small') ? 'http://www.placeholder-image.com/image/200x40' : Editorial::getOption('logo-small');
                        $logo_gallery = !Editorial::getOption('logo-gallery') ? 'http://www.placeholder-image.com/image/131x17' : Editorial::getOption('logo-gallery');

                        ?>
                        <input type="text" name="logo-big" value="<?php echo $logo_big;  ?>" /><br />
                        <input type="text" name="logo-small" value="<?php echo $logo_small; ?>" /><br />
                        <input type="text" name="logo-gallery" value="<?php echo $logo_gallery; ?>" />
                        <p class="note"><?php _e('Specify the images your would like to use as the theme big, small &amp; gallery logo.', 'Editorial'); ?></p>
                        <div class="logos">
                            <img src="<?php echo $logo_big; ?>" alt="Big logo" /><br />
                            <img src="<?php echo $logo_small; ?>" alt="Small logo" />
                            <img src="<?php echo $logo_gallery; ?>" alt="Gallery logo" class="gallery" />
                        </div>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th><?php _e('Typekit settings', 'Editorial'); ?></th>
                <td>
                    <fieldset>
                        <label><?php _e('Enable typekit font', 'Editorial'); ?> <input type="checkbox" name="typekit"<?php echo !Editorial::getOption('typekit') ? '' : ' checked="checked"'; ?> /></label>
                        <p class="note"><?php _e('You will need a <a href="http://typekit.com" target="_blank">typekit</a> account to enable custom font for Editorial template.', 'Editorial'); ?></p>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th><?php _e('Black &amp; white cover photos', 'Editorial'); ?></th>
                <td>
                    <label><?php _e('Enable black &amp; white covers') ?> <input type="checkbox" name="black-and-white"<?php echo !Editorial::getOption('black-and-white') ? '' : ' checked="checked"'; ?> /></label>
                    <p class="note"><?php _e('Black &amp; white photos will appear on the main page but not on subpages.', 'Editorial'); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Theme notifications', 'Editorial'); ?></th>
                <td>
                    <label><?php _e('Disable wordpress notifications', 'Editorial') ?> <input type="checkbox" name="disable-admin-notices"<?php echo !Editorial::getOption('disable-admin-notices') ? '' : ' checked="checked"'; ?> /></label>
                    <p class="note"><?php _e('If you disable wordpress notifications you will not see any typekit or theme update notifications.', 'Editorial'); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Karma settings', 'Editorial'); ?></th>
                <td>
                    <label><?php _e('Enable karma comment votes', 'Editorial') ?> <input type="checkbox" name="karma"<?php echo !Editorial::getOption('karma') ? '' : ' checked="checked"'; ?> /></label><br />
                    <input type="text" name="karma-treshold" value="<?php echo Editorial::getOption('karma-treshold'); ?>" />
                    <p class="note karma"><?php _e('Karma treashold controls when the comments with downvotes are hidden.', 'Editorial'); ?></p>
                </td>
            </tr>
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
            <tr>
                <th><?php _e('Copyright', 'Editorial'); ?></th>
                <td>
                    <input type="text" name="copyright" value="<?php echo Editorial::getOption('copyright'); ?>" placeholder="<?php _e('Copyright', 'Editorial'); ?>" />
                    <p class="note"><?php _e('Copyright is visible in site footer.', 'Editorial'); ?></p>
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'Editorial'); ?>"></p>
    </form>