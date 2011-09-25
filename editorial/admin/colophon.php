    <h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Colophon', 'Editorial'); ?></h2>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
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
    <p><?php _e('Set up your colophon page to your liking. Order authors, set page title and enter your about details, then check it out live.'); ?></p>
    <h3><?php _e('Content', 'Editorial'); ?></h3>
    <form action="" method="post">
    <p><label>Title:<br />
    <input type="text" name="colophon_title" value="<?php echo Editorial::getOption('colophon_title') ? Editorial::getOption('colophon_title') : _e('Impressum', 'Editorial'); ?>" placeholder="<?php _e('Colophon title', 'Editorial'); ?>" /></label></p>
    <label>Content:
    <div id="postdivrich" class="postarea">
        <div id="quicktags">
        <script type="text/javascript">
        /* <![CDATA[ */
        var quicktagsL10n = {
            quickLinks: "(Quick Links)",
            wordLookup: "Enter a word to look up:",
            dictionaryLookup: "Dictionary lookup",
            lookup: "lookup",
            closeAllOpenTags: "Close all open tags",
            closeTags: "close tags",
            enterURL: "Enter the URL",
            enterImageURL: "Enter the URL of the image",
            enterImageDescription: "Enter a description of the image",
            fullscreen: "fullscreen",
            toggleFullscreen: "Toggle fullscreen mode"
        };
        try{convertEntities(quicktagsL10n);}catch(e){};
        /* ]]> */
        </script>
        <script type="text/javascript" src="http://editorial.local/wp-includes/js/quicktags.js?ver=20110502"></script>
        <script type="text/javascript">edToolbar()</script>
    </div>

    <div id="editorcontainer"><textarea rows="15" cols="40" name="colophon_content" tabindex="2" id="content"><?php echo Editorial::getOption('colophon_content') ?></textarea></div>
        <script type="text/javascript">
        edCanvas = document.getElementById('content');
        </script>
        <table id="post-status-info" cellspacing="0">
            <tbody><tr>
                <td id="wp-word-count">&nbsp;</td>
            </tr></tbody>
        </table>
    </div></label>

    <h3><?php _e('Authors', 'Editorial'); ?></h3>
    <p><?php _e('Order users as they will appear on the colophon page. You can uncheck the ones that you do not wish to show there.'); ?></p>

    <?php



    $users = get_users(array(
        'who' => 'author',
        'exclude' => array(1),
    ));
    if (count($users))
    {
        echo '<ul id="authors">';
        // load saved order and
        $authors = Editorial::getOption('authors');
        $alreadyShown = array();
        if (count($authors))
        {
            foreach ($authors as $id => $title)
            {
                $data = get_userdata($id);
                if (!$data)
                {
                    // user data not loaded
                    continue;
                }
                Editorial_Admin::displayUser($data, $title);
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
            Editorial_Admin::displayUser($user, '', !(bool)$authors);
        }
        echo '</ul>';
    }

    ?>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'editorial'); ?>">
    </p>
    </form>