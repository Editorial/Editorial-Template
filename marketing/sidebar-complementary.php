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
<!--
<div class="enquiry-form">
	<h3 id="enquiry-trigger"><em>Send Enquiry</em></h3>
	<?php
	/* HTML and tags for Contact Form 7:
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
	*/
	if($cfid) echo do_shortcode('[contact-form-7 id="' . $cfid . '" title="Enquiry"]'); ?>
</div>
-->
	<div class="send-inquiry">
		<form class="inquiry" method="post">
			<h2><em>Send inquiry</em></h2>
			<div class="adapt">
				<fieldset class="title-input">
					<label for="i-message">Dear Editorial team,</label>
					<textarea id="i-message" name="i-message" rows="6" cols="40">I am writing about ...</textarea>
				</fieldset>
				<fieldset class="message-input">
					<label for="i-name">Yours sincerely,</label>
					<input type="text" name="i-name" id="i-name" placeholder="Your name">
					<input type="text" name="i-email" id="i-email" placeholder="Your e-mail address">
				</fieldset>
				<fieldset class="action">
					<span class="cancel"><a href="/">Cancel and close</a> or</span> <input type="submit" class="sendit" value="Send inquiry">
				</fieldset>
			</div>
		</form>
	</div>
