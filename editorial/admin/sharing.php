	<h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Sharing', 'Editorial'); ?></h2>
	
<div class="poststuff">
    <!-- #post-body .metabox-holder goes here -->
	<div id="post-body" class="metabox-holder columns-2">
    <!-- meta box containers here -->
  	
  	<div id="postbox-container" class="postbox-container">	
			<div id="normal-sortables" class="meta-box-sortables ui-sortable">
			
			<form action="" method="post">
				<?php wp_nonce_field( 'some-action-nonce' );
			    /* Used to save closed meta boxes and their order */
			    wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
			    wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>



			  <div class="postbox " style="display: block; ">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php _e('Twitter share', 'Editorial'); ?></span></h3>
					<div class="inside">
		  			<div class="table table_content">
		  				<label><?php _e('Enable twitter share', 'Editorial'); ?> <input type="checkbox" name="twitter-share"<?php echo !Editorial::getOption('twitter-share') ? '' : ' checked="checked"'; ?> /></label><br />
							<input type="text" name="twitter-account" value="<?php echo Editorial::getOption('twitter-account'); ?>" placeholder="<?php _e('Your twitter account', 'Editorial'); ?>" /><br />
							<input type="text" name="twitter-related" value="<?php echo Editorial::getOption('twitter-related'); ?>" placeholder="<?php _e('Related account', 'Editorial'); ?>" />
							<p class="note"><?php _e('Twitter share is visible on article page.', 'Editorial'); ?></p>
		  			</div>
		  		</div>
			  </div>


			  <div class="postbox " style="display: block; ">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php _e('Facebook share', 'Editorial'); ?></span></h3>
					<div class="inside">
		  			<div class="table table_content">
		  				<label><?php _e('Enable facebook share', 'Editorial'); ?> <input type="checkbox" name="facebook-share"<?php echo !Editorial::getOption('facebook-share') ? '' : ' checked="checked"'; ?> /></label>
							<p class="note"><?php _e('Facebook share is visible on article page.', 'Editorial'); ?></p>
		  			</div>
		  		</div>
			  </div>


			  <div class="postbox " style="display: block; ">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span><?php _e('Google share', 'Editorial'); ?></span></h3>
					<div class="inside">
		  			<div class="table table_content">
		  				<label><?php _e('Enable google share', 'Editorial'); ?> <input type="checkbox" name="google-share"<?php echo !Editorial::getOption('google-share') ? '' : ' checked="checked"'; ?> /></label>
							<p class="note"><?php _e('Google share is visible on article page.', 'Editorial'); ?></p>
		  			</div>
		  		</div>
			  </div>


			  <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'Editorial'); ?>"></p>

			</form>


			</div>
		</div>






    <?php include 'faq.php'; ?>
  </div>
</div>































