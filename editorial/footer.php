<?php
// show footer if not on 404 page
if ($EditorialId != 'notfound')
{
?>

<?php
    if (!($EditorialId == 'gallery' && Editorial::isMobileDevice()))
    {
	   get_sidebar('footer');
    }
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
					'
		<li><a href="%1$s" title="%3$s %2$s">%2$s</a>',
					get_category_feed_link($category->cat_ID),
					$category->name,
					__('Subscribe', 'Editorial')
				);
			}
?>

	</ul>
	<section>
		
<?php
		
	    if (has_nav_menu('footer-nav'))
	    {
	        // display footer menu
            $settings = array(
                'theme_location' => 'footer-nav',
                'container'      => 'nav',
                //'container_class'=> 'nav',
                'menu_id'        => false,
                'menu_class'     => 'xoxo',
                'depth'          => 1,
                'walker'         => new EditorialNav(),
            );
            wp_nav_menu($settings);
	    }
			
?>
	</section>
	<small id="copyright"><?php echo Editorial::getOption('copyright'); ?><?php _e('Powered by <a href="http://wordpress.com">Wordpress</a> and <em id="editorial" class="vcard"><a href="http://editorialtemplate.com/" class="fn org url">Editorial template</a></em>.', 'Editorial') ?></small>
</footer>
<?php
}
?>

<?php if (Editorial::isMobileDevice()) { ?>
<script type="text/javascript" src="<?php echo get_bloginfo('template_directory'); ?>/js/libs/hide-address-bar.js"></script>
<?php } ?>

<script src="<?php echo get_bloginfo('template_directory'); ?>/js/plugins.js"></script>
<script src="<?php echo get_bloginfo('template_directory'); ?>/js/script.js"></script>
<?php wp_footer(); ?>
<noscript>Your browser does not support JavaScript!</noscript>

</body>

</html>