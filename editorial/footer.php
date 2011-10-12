<?php
// show footer if not on 404 page
if ($EditorialId != 'notfound')
{
	?>
	<footer id="footer" class="clear" role="contentinfo">
		<h3>Subscribe</h3>
		<ul id="rss">
			<li><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to all categories">All categories</a></li>
			<?php
			// list categories
			foreach (get_categories() as $category)
			{
				printf(
					'<li><a href="%1$s" title="%3$s %2$s">%2$s</a>',
					get_category_feed_link($category->cat_ID),
					$category->name,
					__('Subscribe', 'Editorial')
				);
			}
			?>
		</ul>
		<?php get_sidebar('footer'); ?>
		<div id="temp">
		<section>
			<nav role="navigation">
				<ul>
					<li><a href="<?php echo (defined('WP_SITEURL'))? WP_SITEURL : get_bloginfo('url'); ?>/colophon.php"><?php _e('Colophon', 'Editorial'); ?></a></li>
				</ul>
			</nav>
			<?php
			// display footer menu
			$settings = array(
				'theme_location' => 'footer-nav',
				'container'      => false,
				'menu_class'     => 'xoxo',
				'menu_id'        => '',
				'depth'          => 1,
				'walker'         => new EditorialNav(),
			);
			wp_nav_menu($settings);
			?>
		</section>
		<small id="copyright"><?php echo Editorial::getOption('copyright'); ?><br>
		<?php _e('Powered by <a href="http://wordpress.com">Wordpress</a> and <a href="http://editorialtemplate.com/">Editorial template</a>.', 'Editorial') ?></small>
		</div>
	</footer>
	<?php
}
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/libs/jquery-1.5.1.min.js">\x3C/script>')</script>
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/plugins.js"></script>
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/script.js"></script>
<?php if ($needsHTML5player) { ?>
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/libs/mediaelement-and-player.min.js"></script>
<?php } ?>
<!--[if (lt IE 9) & (!IEMobile)]><script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/libs/DOMAssistantCompressed-2.8.js"></script>
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/libs/respond.min.js"></script><![endif]-->
<!--<script>
var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
s.parentNode.insertBefore(g,s)}(document,'script'));
</script>-->
<?php wp_footer(); ?>
<noscript>Your browser does not support JavaScript!</noscript>
</body>

</html>