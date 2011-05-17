    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
    <script type="text/javascript">
    // When the document is ready set up our sortable with it's inherant function(s)
    jQuery(document).ready(function() {
       jQuery("#authors").sortable({
        update : function () {
          var order = jQuery('#authors').sortable('serialize');
          jQuery("#sorted").val(order);
        }
      });
    });
    </script>
    <p><?php _e('Order users as they will appear on the colophon page.'); ?></p>
    <?php

    $users = get_users(array(
        'who' => 'author',
        'exclude' => array(1),
    ));
    if (count($users))
    {
        echo '<ul id="authors">';
        foreach ($users as $user)
        {
            $data = get_userdata($user->ID);
            printf('<li id="user_%d">%s</li>', $user->ID, $user->display_name);
        }
        echo '</ul>';
    }

    ?>
    <form action="" method="post">
    <p class="submit">
        <input type="hidden" name="sorted" id="sorted" value="" />
        <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'editorial'); ?>">
    </p>
    </form>