<?php
/**
 * The template for displaying the footer.
 *
 * @package Editorial
 * @subpackage Marketing
 */
?>

<footer id="footer" role="contentinfo">
	<?php if (!is_page_template('cart.php')) { ?>
	<div class="updates">
		<div class="adapt">
			<section class="subscription">
				<h3>Subscribe to our newsletter</h3>
				<p>The very best way to be first in line to hear about our discounts or keep on track with our development,
				updates etc.</p>
				<form id="subscribe-form" method="post">
					<fieldset>
						<legend class="v-hidden">Subscription</legend>
						<label for="email" class="v-hidden">Email</label>
						<input type="email" id="email" name="email" placeholder="Your e-mail address">
						<input type="submit" id="subscribe" class="continue" value="Subscribe">
					</fieldset>
				</form>
			</section>
			<section class="connect hentry">
				<h3 class="entry-title">Keep in touch</h3>
				<p class="entry-summary">Don’t be a stranger and drop us a line or two. We love to talk about online publishing but mostly we
				love to hear your thoughts and feedback.</p>
				<div class="twitter entry-content">
					<blockquote>
						<p>Our custom icons designed by <a href="/" target="_blank">@benedikrok</a> spoted in reality:
						<a href="http://bit.ly/lu2lL0" target="_blank">http://bit.ly/lu2lL0</a> ;
						more preview on <a href="/" target="_blank">@dribbble</a>:
						<a href="http://bit.ly/kgVQHl" target="_blank">http://bit.ly/kgVQHl</a></p>
					</blockquote>
					<iframe id="follow" src="http://platform.twitter.com/widgets/follow_button.html?screen_name=Editorialtheme&amp;show_count=false"></iframe>
				</div>
				<div class="v-hidden">
					<time class="published" pubdate datetime="2011-10-20T20:00:00+01:00">10/20/2011</time>
					<a class="author include" href="#brand">Editorial</a>
				</div>
			</section>
		</div>
	</div>
	<?php } ?>
	<div class="adapt">
		<nav class="support" role="navigation">
			<div class="col">
				<h4>Help & support</h4>
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
			<p><strong>Copyright (c) 2011 Editorial.</strong></p>
			<p>Brought to you with help of <a href="/" target="_blank">@malarky</a>’s 320-up boilerplate
			and <a href="/" target="_blank">@benedikrok</a>’s icon wizardry.</p>
		</section>
	</div>
</footer>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.6.2.min.js">\x3C/script>')</script>
<script src="js/plugins.js"></script>
<script src="js/script.js"></script>
<!--<script>
var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
s.parentNode.insertBefore(g,s)}(document,'script'));
</script>-->
<noscript>Your browser does not support JavaScript!</noscript>
<?php wp_footer(); ?>
</body>

</html>
