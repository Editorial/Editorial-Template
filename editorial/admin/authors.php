	<h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Team members', 'Editorial'); ?></h2>
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
	<p><?php _e('Order users as they will appear on the colophon page. You can uncheck the ones that you do not wish to show there.'); ?></p>
	<form action="" method="post">
	<?php

	$users = get_users(array(
		'who' => 'author',
		'exclude' => array(1),
	));
	if (count($users))
	{
		echo '<ul id="authors">';
		// load saved order and
		$authors = Editorial::getOption('authors') ? Editorial::getOption('authors') : array();
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
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'editorial'); ?>">
	</p>
	</form>