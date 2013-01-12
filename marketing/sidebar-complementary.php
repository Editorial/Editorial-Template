<?php
/*
 * Pricing & licencing and enquiry form split into this file for
 * easier template inclusion.
 */

// Get options
$opts = get_option('em_theme_options');
// Get contact form ID
$cfid = false;
if(isset($opts['enquiry'])) {
	$cfid = $opts['enquiry'];
}
?>
	<div class="send-inquiry">
	<?php
	if($cfid) echo do_shortcode('[contact-form-7 id="' . $cfid . '" title="Enquiry"]'); ?>
	</div>
</div>