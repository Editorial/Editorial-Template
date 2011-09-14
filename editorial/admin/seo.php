    <h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('SEO', 'Editorial'); ?></h2>
    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th><?php _e('Meta keywords', 'Editorial'); ?></th>
                <td>
                    <input type="text" name="meta-keywords" value="<?php echo Editorial::getOption('meta-keywords'); ?>" placeholder="<?php _e('Keywords', 'Editorial'); ?>" />
                    <p class="note"><?php _e('Separate keywords with a comma (,)', 'Editorial'); ?></p>
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'Editorial'); ?>"></p>
    </form>