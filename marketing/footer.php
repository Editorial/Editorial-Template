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
	if ( !is_page_template('cart.php') && !is_page_template('manager.php') )
	{
?>
	<div class="updates">
		<div class="adapt">
			<section class="subscription">
				<h3>Subscribe to our newsletter</h3>
				<p>We know you are curious! Be the first to know of all our special little secrets and let us give
				you the news first hand. Hear about our special offers or keep on track with our development and updates.</p>
				<form id="subscribe-form" method="post" action="http://editorialtemplate.us2.list-manage.com/subscribe/post?u=1a3daff254c9bd337e91cbe21&amp;id=356cc54588" name="mc-embedded-subscribe-form">
					<fieldset>
						<legend class="v-hidden">Subscription</legend>
						<label for="email" class="v-hidden">Email</label>
						<input type="email" id="email" name="EMAIL" placeholder="Your e-mail address" required>
						<input type="submit" id="subscribe" class="continue" name="subscribe" value="Subscribe">
					</fieldset>
				</form>
			</section>
			<article class="connect">
				<h3>Letʼs stay in touch</h3>
				<p>Feedback is great. And not just with spicing up the cool guitar solos. Don’t be a stranger and drop
				us a line or two. We love to talk about online publishing and would love to hear from you too.</p>
				<div class="twitter"></div>
				<a href="https://twitter.com/editorialtheme" class="twitter-follow-button" data-show-count="false">Follow @editorialtheme</a>
				<script src="//platform.twitter.com/widgets.js"></script>
			</article>
		</div>
	</div>
<?php
			}
?>
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
			<p><strong>Copyright (c) 2011 <em id="editorial" class="vcard"><a href="http://editorialtemplate.com/" class="fn org url">Editorial</a></em>.</strong></p>
			<p>
				Brought to you with help of
				<span class="vcard">
					<a href="http://twitter.com/malarkey" class="url fn nickname" target="_blank">@malarkey</a>’s
					<span class="note">320-up boilerplate</span>,
				</span>
				<span class="vcard">
					<a href="http://twitter.com/benedikrok" class="url fn nickname" target="_blank">@benedikrok</a>’s
				 	<span class="note">icon wizardry</span>
				</span>
				<span class="vcard">
					and <span class="note">photography</span> by
					<a href="http://twitter.com/jakavinsek" class="url fn nickname" target="_blank">@jakavinsek</a>.
				</span>
			</p>
		</section>
	</div>
</footer>

<?php } ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.js"></script>
<script>window.jQuery || document.write('<script src="<?php bloginfo( 'template_directory' ); ?>/assets/js/libs/jquery-1.6.4.min.js">\x3C/script>')</script>
<script src="<?php bloginfo( 'template_directory' ); ?>/assets/js/plugins.js"></script>
<script src="<?php bloginfo( 'template_directory' ); ?>/assets/js/script.js"></script>
<noscript>Your browser does not support JavaScript!</noscript>
<?php wp_footer(); ?>
</body>

</html>
