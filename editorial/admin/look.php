	<h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Look &amp; Feel', 'Editorial'); ?></h2>
	<form action="" method="post">
		<table class="form-table">
			<tr>
				<th><?php _e('Logo', 'Editorial'); ?></th>
				<td>
					<fieldset>
						<?php

						$logo_big     = !Editorial::getOption('logo-big') ? 'http://www.placeholder-image.com/image/356x70' : Editorial::getOption('logo-big');
						$logo_small   = !Editorial::getOption('logo-small') ? 'http://www.placeholder-image.com/image/200x40' : Editorial::getOption('logo-small');
						$logo_gallery = !Editorial::getOption('logo-gallery') ? 'http://www.placeholder-image.com/image/131x17' : Editorial::getOption('logo-gallery');

						?>
						<p class="logos"><img src="<?php echo $logo_big; ?>" alt="Big logo" /></p>
						<input type="text" name="logo-big" value="<?php echo $logo_big;  ?>" />
						<p class="note"><?php _e('Big logo is displayed on first page only. Recommended dimension 356x70px', 'Editorial'); ?></p>
						<p class="logos"><img src="<?php echo $logo_small; ?>" alt="Small logo" /></p>
						<input type="text" name="logo-small" value="<?php echo $logo_small; ?>" />
						<p class="note"><?php _e('Small logo is displayd on all subpages. Reommended dimension 200x40px', 'Editorial'); ?></p>
						<p class="logos"><img src="<?php echo $logo_gallery; ?>" alt="Gallery logo" class="gallery" /></p>
						<input type="text" name="logo-gallery" value="<?php echo $logo_gallery; ?>" />
						<p class="note"><?php _e('Gallery logo is displayed in mobile version of the gallery.<br/>Recommended dimension 131x17, prepared for black background.', 'Editorial'); ?></p>
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
				<th><?php _e('Karma settings', 'Editorial'); ?></th>
				<td>
					<label><?php _e('Enable karma comment votes', 'Editorial') ?> <input type="checkbox" name="karma"<?php echo !Editorial::getOption('karma') ? '' : ' checked="checked"'; ?> /></label><br />
					<input type="text" name="karma-treshold" value="<?php echo Editorial::getOption('karma-treshold'); ?>" />
					<p class="note karma"><?php _e('Karma treashold controls when the comments with downvotes are hidden.', 'Editorial'); ?></p>
				</td>
			</tr>
			<tr>
				<th><?php _e('Copyright', 'Editorial'); ?></th>
				<td>
					<input type="text" name="copyright" value="<?php echo Editorial::getOption('copyright'); ?>" placeholder="<?php _e('Copyright', 'Editorial'); ?>" />
					<p class="note"><?php _e('Copyright is visible in site footer.', 'Editorial'); ?></p>
				</td>
			</tr>
			<tr>
				<th><?php _e('Theme notifications', 'Editorial'); ?></th>
				<td>
					<label><?php _e('Disable wordpress notifications', 'Editorial') ?> <input type="checkbox" name="disable-admin-notices"<?php echo !Editorial::getOption('disable-admin-notices') ? '' : ' checked="checked"'; ?> /></label>
					<p class="note"><?php _e('If you disable wordpress notifications you will not see any typekit or theme update notifications.', 'Editorial'); ?></p>
				</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'Editorial'); ?>"></p>
	</form>