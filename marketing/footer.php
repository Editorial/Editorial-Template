<?php
/**
 * The template for displaying the footer.
 *
 * @package Editorial
 * @subpackage Marketing
 */

if (!is_404())
{

?>
<footer id="footer" role="contentinfo">
<?php
	if ( !is_page_template('cart.php') && !is_page_template('manager.php') && !is_page('trial'))
	{
		$Purchase = new Purchase();
		$currentCount = $Purchase->getCount();
		$currentPrice = $Purchase->getPricingForCount($currentCount);
		if(!is_post_type_archive('faq') && !is_singular('faq')) {
?>
	<div class="try-and-buy">
		<h2><em>Price <span>&amp;</span> licencing</em></h2>
		<section class="licencing">
			<p class="early-bird"><strong>Exculsive early bird prices</strong> for the first 1000 licences:</p>
			<ul class="price-flow pf-mobile">
				<li class="line-1 step-1 current">
					<em>&euro;10</em>
					<span>0</span>
					<b>0</b>
					<i></i>
				</li>
				<li class="line-2">
					<span>50</span>
					<i></i>
				</li>
				<li class="line-3 step-2">
					<em>&euro;20</em>
					<span>100</span>
					<b>100</b>
					<i></i>
				</li>
				<li class="line-4">
					<span>150</span>
					<i></i>
				</li>
				<li class="line-5">
					<span>200</span>
					<b>200</b>
					<i></i>
				</li>
				<li class="line-6">
					<span>250</span>
					<i></i>
				</li>
				<li class="line-7 step-3">
					<em>&euro;30</em>
					<span>300</span>
					<b>300</b>
					<i></i>
				</li>
				<li class="line-8">
					<span>350</span>
					<i></i>
				</li>
				<li class="line-9">
					<span>400</span>
					<b>400</b>
					<i></i>
				</li>
				<li class="line-10">
					<span>450</span>
					<i></i>
				</li>
				<li class="line-11">
					<span>500</span>
					<b>500</b>
					<i></i>
				</li>
				<li class="line-12">
					<span>550</span>
					<i></i>
				</li>
				<li class="line-13 step-4">
					<em>&euro;40</em>
					<span>600</span>
					<b>600</b>
					<i></i>
				</li>
				<li class="line-14">
					<span>650</span>
					<i></i>
				</li>
				<li class="line-15">
					<span>700</span>
					<b>700</b>
					<i></i>
				</li>
				<li class="line-16">
					<span>750</span>
					<i></i>
				</li>
				<li class="line-17">
					<span>800</span>
					<b>800</b>
					<i></i>
				</li>
				<li class="line-18">
					<span>850</span>
					<i></i>
				</li>
				<li class="line-19">
					<span>900</span>
					<b>900</b>
					<i></i>
				</li>
				<li class="line-20">
					<span>950</span>
					<i></i>
				</li>
				<li class="line-21 step-5">
					<em>&euro;50</em>
					<span>1000</span>
					<b>1000</b>
					<i></i>
				</li>
				<li class="line-22">
					<span>1050</span>
					<i></i>
				</li>
				<li class="line-23">
					<span>1100</span>
					<i></i>
				</li>
				<li class="line-24">
					<span>1150</span>
					<i></i>
				</li>
				<li class="line-25">
					<span>1200</span>
					<b>&infin;</b>
					<i></i>
				</li>
				<li class="line-26">
					<span>1250</span>
					<i></i>
				</li>
			</ul>
			<ul class="price-flow pf-other">
			<?php

			$stepCount = 0;
			for ($i = 1; $i <= 63; $i++)
			{
				$count = ($i-1) * 20;
				$price = $Purchase->getPricingForCount($count);
				$step  = $Purchase->hasStepAtCount($count);
				if ($step)
				{
					$stepCount++;
				}
				$style = '';
				if ($currentPrice == $price && $currentCount >= $count && $currentCount < $count+20)
				{
					$style = 'current';
				}
				else if ($price < $currentPrice)
				{
					$style = 'sold';
				}
				else if ($price == $currentPrice && $count/20 <= ($currentCount/20)-1)
				{
					$style = 'active';
				}
				printf('<li class="line-%d %s %s">
					%s
					<span>%d</span>
					%s
					<i></i>
				</li>',
					$i,
					$step ? sprintf('step-%d', $stepCount) : '',
					$style,
					$step ? sprintf('<em>&euro;%d</em>', $price) : '',
					$count,
					$i == 61 ? '<b>&infin;</b>' : ($count <= 1000 && $count % 100 == 0 ? sprintf('<b>%d</b>', $count) : '')
				);
			}

			?>
			</ul>
		</section>
		<section class="price-tag">
			<h4><em>Edit</em>orial<sup>2</sup></h4>
			<ul class="included">
				<li>All listed features</li>
				<li>Free compatibility updates</li>
				<li>Access to support forums</li>
				<li>Complete code documentation</li>
				<li class="licence"><strong>1</strong> Domain licence</li>
			</ul>
			<p class="price">
				<span class="label">Total</span>
				<strong><em>&euro;</em><b id="pricetag"><?php echo $currentPrice; ?></b></strong>
				<a href="/purchase/" class="go">Purchase</a>
			</p>
		</section>
		<div class="try">
			<section class="to-trial">
				<h3>Free 14 days trial</h3>
				<p>It will take you less than a minute to start testing. No credit card required. Experience it for yourself.
				<a href="/trial/" class="go-try"><em>Start your free trial</em></a></p>
			</section>
			<section class="to-demo">
				<h3>Demonstration</h3>
				<p>Walk a mile in your reader’s shoes. Experience the beauty of digital publishing done right.
				<a href="http://demo.editorialtemplate.com/" class="go-try"><em>See it in action</em></a></p>
			</section>
		</div>
	</div>
	<?php
		get_sidebar('complementary');
	}
	?>
	<div class="updates">
		<div class="adapt">
			<article class="connect">
				<h3>Letʼs stay in touch</h3>
				<p>Feedback is great. And not just for spicing up guitar solos. Don’t be a stranger and drop us
				a line or two on Twitter or Facebook. We would love to hear your thoughts on Editorial or chat
				about digital publishing in general.</p>
				<ul class="social">
					<li class="twitter"><a href="http://twitter.com/editorialtheme">Follow on Twitter</a></li>
					<li class="facebook"><a href="http://facebook.com/editorialtheme">Like on Facebook</a></li>
				</ul>
			</article>
			<section class="subscription">
				<h3>Subscribe to our newsletter</h3>
				<p>We know you are curious! Be the first to know of all our special little secrets and let
				us give you the news first hand. Hear about our special offers or keep on track with our
				development and updates.</p>
				<form id="subscribe-form" method="post">
					<fieldset>
						<legend class="v-hidden">Subscription</legend>
						<label for="email" class="v-hidden">Email</label>
						<input type="email" id="email" name="email" placeholder="Your e-mail address">
						<input type="submit" id="subscribe" class="continue" value="Subscribe">
					</fieldset>
				</form>
			</section>
		</div>
	</div>
<?php
			}
?>
	<div class="bgr">
		<div class="adapt">
			<nav class="support" role="navigation">
				<div class="col">
					<h4>Help &amp; support</h4>
					<?php

					$settings = array(
						'theme_location' => 'help-nav',
						'container'      => false,
						'menu_class'     => '',
						'menu_id'        => '',
						'depth'          => 1,
						'walker'         => new EditorialNav(),
					);
					wp_nav_menu($settings);

					?>
				</div>
				<div class="col">
					<h4>About</h4>
					<?php

					$settings = array(
						'theme_location' => 'about-nav',
						'container'      => false,
						'menu_class'     => '',
						'menu_id'        => '',
						'depth'          => 1,
						'walker'         => new EditorialNav(),
					);
					wp_nav_menu($settings);

					?>
				</div>
				<div class="col">
					<h4>Legal notice</h4>
					<?php

					$settings = array(
						'theme_location' => 'legal-nav',
						'container'      => false,
						'menu_class'     => '',
						'menu_id'        => '',
						'depth'          => 1,
						'walker'         => new EditorialNav(),
					);
					wp_nav_menu($settings);

					?>
				</div>
			</nav>
			<section class="copyright">
				<p><strong>Copyright (c) 2011-<?php echo date('Y'); ?> <em id="editorial" class="vcard"><a href="http://editorialtemplate.com/" class="fn org url">Editorial</a></em>.</strong></p>
				<p>
					Powered by WordPress and burning passion.<br />
					<a href="/terms/">Terms of use</a> • <a href="/privacy/">Privacy policy</a>
				</p>
			</section>
		</div>
	</div>
</footer>

<?php } ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>
<script>window.jQuery || document.write('<script src="<?php bloginfo( 'template_directory' ); ?>/assets/js/libs/jquery-1.8.3.min.js">\x3C/script>')</script>
<script src="<?php bloginfo( 'template_directory' ); ?>/assets/js/plugins.js"></script>
<script src="<?php bloginfo( 'template_directory' ); ?>/assets/js/script.js"></script>
<?php if(is_front_page()) : ?>
<script>
// change call to action for returning visitors
var cta;
if(!localStorage.getItem('returning')) {
	// first time visitor
	localStorage.setItem('returning', true);
	cta = '<a href="/features/" class="go-alt">Learn More</a> or ';
} else {
	// returning visitor
	cta = '<a href="/purchase/" class="go">Purchase</a> or ';
}
jQuery(function($) { $('#cta').prepend(cta); });
</script>
<?php endif; ?>
<noscript>Your browser does not support JavaScript!</noscript>
<?php wp_footer(); ?>
</body>

</html>
