    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th><?php _e('Theme logo image', 'editorial'); ?></th>
                <td>
                    <input type="text" name="logo" value="http://www.placeholder-image.com/image/144x87" />
                    <p>Specify the image your would like to use as the theme logo.</p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Typekit settings', 'editorial'); ?></th>
                <td>
                    <fieldset>
                        <?php _e('Enable typekit font', 'editorial'); ?> <input type="checkbox" name="typekit-enabled" />
                        <p class="note"><?php _e('You will need a <a href="http://typekit.com" target="_blank">typekit</a> account to enable custom font for Editorial template.', 'editorial'); ?></p>
                    </fieldset>
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'editorial'); ?>"></p>
    </form>