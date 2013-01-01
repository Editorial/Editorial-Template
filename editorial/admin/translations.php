  <h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Translations', 'Editorial'); ?></h2>

<div class="poststuff">
    <!-- #post-body .metabox-holder goes here -->
<div id="post-body" class="metabox-holder columns-2">
    <!-- meta box containers here -->


  <div id="postbox-container" class="postbox-container">  
  <div id="normal-sortables" class="meta-box-sortables ui-sortable">

    <form action="" method="post">
      <?php wp_nonce_field( 'some-action-nonce' );
        wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
        wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>


      <div class="postbox " style="display: block; ">
        <div class="handlediv" title="Click to toggle"><br></div>
        <h3 class="hndle"><span><?php _e('Translations', 'Editorial'); ?></span></h3>
        <div class="inside">
          <div class="table table_content">
            <!-- <input type="text" name="twitter-account" value="<?php echo Editorial::getOption('twitter-account'); ?>" placeholder="<?php _e('Your twitter account', 'Editorial'); ?>" /><br />
            <input type="text" name="twitter-related" value="<?php echo Editorial::getOption('twitter-related'); ?>" placeholder="<?php _e('Related account', 'Editorial'); ?>" /> -->

            <?php

            $translations = Editorial::getOption('translations');
            foreach ($translations as $key => $value)
            { ?>
          <fieldset class="e-translations">
            <label><?php echo $key; ?></label>
            <input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
          </fieldset>
            <?php }

            ?>

          </div>
        </div>
      </div>


      <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'editorial'); ?>">
      </p>
    </form>

  </div>
  </div>



<?php include 'faq_sharing.php'; ?>
</div>
</div>