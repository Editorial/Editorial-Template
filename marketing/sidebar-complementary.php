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
<aside class="pricing" role="complementary">

<div class="pricing">
	<h3><em>Price <span>&</span> licencing</em></h3>
	<figure>
		<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/sheets.png" alt="Editorial sheets">
	</figure>
	<div class="price-tag">
		<h4>Editorial theme</h4>
		<ul class="included">
			<li>All listed features</li>
			<li>Free compatibility updates</li>
			<li>Access to support forums</li>
			<li>Complete code documentation</li>
			<li class="licence"><strong>1</strong> Domain licence</li>
		</ul>
		<p class="price">
			<span class="label">Total</span>
			<strong><em>&euro;</em>150.<sup>00</sup></strong>
			<a href="/purchase/" class="go">Purchase</a>
		</p>
	</div>
</div>

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

</aside>
