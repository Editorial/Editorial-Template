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
/* HTML and tags for Contact Form 7

Please update this comment with the HTML from CF7 field in admin dashboard.
Do not delete.
---------------------------------
<div class="enquiry-message">
<label for="msg">Dear Editorial team,</label>[textarea* msg 60x3 id:msg class:enquiry-textarea watermark "I am writing about …"]
</div>
<div class="enquiry-contact">
<label for="name">Yours sincerely,</label> [text* name id:name class:enquiry-text watermark "Your name"]
[email* uemail id:uemail class:enquiry-text watermark "Your e-mail"]
</div>
<div class="enquiry-actions">
<a href="" id="enquiry-cancel">Cancel and close</a> or [submit class:enquiry-button "Send Enquiry"]
</div>
---------------------------------

			<h2><em>Send inquiry</em></h2>
			<div class="adapt">
				<fieldset class="title-input">
					<label for="msg">Dear Editorial team,</label>
					[textarea* msg 40x3 id:msg class:enquiry-textarea watermark "I am writing about …"]
				</fieldset>
				<fieldset class="message-input">
					<label for="name">Yours sincerely,</label>
					[text* name id:name class:enquiry-text watermark "Your name"]
					[email* uemail id:uemail class:enquiry-text watermark "Your e-mail address"]
				</fieldset>
				<fieldset class="action">
					<span class="cancel"><a href="/" id="enquiry-cancel">Cancel and close</a> or</span>
					[submit class:sendit "Send Enquiry"]
				</fieldset>
			</div>

*/
		if($cfid) echo do_shortcode('[contact-form-7 id="' . $cfid . '" title="Enquiry"]'); ?>
	</div>
</div>
