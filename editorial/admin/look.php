    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th><?php _e('Theme logo image', 'editorial'); ?></th>
                <td>
                    <fieldset>
                        <?php

                        $logo_big   = !Editorial::get_option('logo-big') ? 'http://www.placeholder-image.com/image/356x70' : Editorial::get_option('logo-big');
                        $logo_small = !Editorial::get_option('logo-small') ? 'http://www.placeholder-image.com/image/200x40' : Editorial::get_option('logo-small');

                        ?>
                        <input type="text" name="logo-big" value="<?php echo $logo_big;  ?>" /><br />
                        <input type="text" name="logo-small" value="<?php echo $logo_small; ?>" />
                        <p class="note"><?php _e('Specify the images your would like to use as the theme big &amp; small logo.', 'editorial'); ?></p>
                        <div class="logos">
                            <img src="<?php echo $logo_big; ?>" alt="Big logo" />
                            <img src="<?php echo $logo_small; ?>" alt="Small logo" />
                        </div>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th><?php _e('Typekit settings', 'editorial'); ?></th>
                <td>
                    <fieldset>
                        <label><?php _e('Enable typekit font', 'editorial'); ?> <input type="checkbox" name="typekit"<?php echo !Editorial::get_option('typekit') ? '' : ' checked="checked"'; ?> /></label>
                        <p class="note"><?php _e('You will need a <a href="http://typekit.com" target="_blank">typekit</a> account to enable custom font for Editorial template.', 'editorial'); ?></p>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th><?php _e('Black &amp; white cover photos', 'editorial'); ?></th>
                <td>
                    <label><?php _e('Enable black &amp; white covers') ?> <input type="checkbox" name="black-and-white"<?php echo !Editorial::get_option('black-and-white') ? '' : ' checked="checked"'; ?> /></label>
                    <p class="note"><?php _e('Black &amp; white photos will appear on the main page but not on subpages.', 'editorial'); ?></p>
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'editorial'); ?>"></p>
    </form>