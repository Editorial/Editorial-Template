  <?php
  $translations = Editorial::getOption('translations');
  ?>

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


    <?php
        foreach ($translations as $section => $items)
      { ?>
      <div class="postbox " style="display: block; ">
        <div class="handlediv" title="Click to toggle"><br></div>
        <h3 class="hndle"><span><?php echo str_replace('_', ' ', $section); ?></span></h3>
        <div class="inside">
          <div class="table table_content">

            <?php
            foreach ($items as $key => $value)
            { ?>
          <fieldset class="e-translations">
            <label><?php echo $key; ?></label>
            <textarea name="translations[<?php echo $section; ?>][<?php echo $key; ?>]"><?php echo $value; ?></textarea>
          </fieldset>
            <?php } ?>

          </div>
        </div>
      </div>
      <?php } ?>


      <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'editorial'); ?>">
      </p>
    </form>

  </div>
  </div>



<?php $faqGroup = 'translations'; include 'faq.php'; ?>
</div>
</div>