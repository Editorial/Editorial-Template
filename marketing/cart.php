<?php
/**
 * Template Name: Cart
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

get_header(); ?>

<div class="content" role="main">
	<section class="process">
		<header>
			<ol class="step1">
				<li id="step1" class="selected">Place order</li>
				<li id="step2">Transaction</li>
				<li id="step3">Download</li>
			</ol>
			<h1><em>Place</em> Order</h1>
		</header>
		<figure>
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/tablets.png" alt="Tablets">
		</figure>
	</section>
	<section class="order">
		<form id="buy-form" method="post" action="/">
			<fieldset class="licenses">
				<legend class="v-hidden">Licenses</legend>
				<ol>
					<li class="item">
						<label for="item">Item</label>
						<input type="text" disabled value="Editorial Wordpress theme" name="item" id="item">
					</li>
					<li class="price-c">
						<label for="price-c">Price</label>
						<input type="text" disabled value="€150" name="price-c" id="price-c">
					</li>
					<li class="licenses-c">
						<label for="licenses-c"># of licenses</label>
						<input type="text" value="1" name="licenses-c" id="licenses-c">
					</li>
					<li class="total">
						<label for="total">Total</label>
						<input type="text" disabled value="€150" name="total" id="total">
					</li>
				</ol>
			</fieldset>
			<fieldset class="domain">
				<legend class="v-hidden">Domain</legend>
				<div class="info">
					<h3>Which domain/s will you be using the theme on?</h3>
					<p>Which domain/s will you be using the theme on?
					Every issued copy of the theme is licensed to a single domain.
					But don’t worry, you can change the domain for your license/s anytime.
					See our <a href="/" target="_blank">FAQ</a> for more information.</p>
				</div>
				<ol id="domains">
					<li>
						<label for="domain-1">Domain 1</label>
						<input type="text" name="domain-1" id="domain-1">
					</li>
					<!--<li>
						<label for="domain-2">Domain 2</label>
						<input type="text" name="domain-2" id="domain-2">
					</li>-->
				</ol>
			</fieldset>
			<fieldset class="payement">
				<legend class="v-hidden">Payement</legend>
				<div class="info">
					<h3>Preferred method of payement:</h3>
				</div>
				<ol class="choose">
					<li>
						<input type="radio" value="1" name="payement" id="payement-1">
						<label for="payement-1">Google Checkout</label>
					</li>
					<li>
						<input type="radio" value="2" name="payement" id="payement-2">
						<label for="payement-2">Paypal</label>
					</li>
					<li>
						<input type="radio" value="3" name="payement" id="payement-3">
						<label for="payement-3">Amazon</label>
					</li>
				</ol>
			</fieldset>
			<fieldset class="tearms">
				<legend class="v-hidden">Tearms</legend>
				<div class="info">
					<h3>Terms of use</h3>
				</div>
				<div class="i-agree">
					<input type="checkbox" value="yes" name="i-agree" id="i-agree">
					<label for="i-agree">I have read and agree with <a href="/" target="_blank">Terms of use</a>.</label>
				</div>
			</fieldset>
			<fieldset class="submit">
				<input type="submit" class="go" value="Proceed to checkout">
			</fieldset>
		</form>
	</section>
</div>

<?php get_footer(); ?>