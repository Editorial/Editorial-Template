<?php
/**
 * Category
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */

$category = get_queried_object();
if (Editorial::isAjax())
{
    output();
    exit();
}

// id depends on the type of the first posts image
// @todo set cookie to save the change
$EditorialId = array_key_exists('list', $_GET) ? 'layout-list' : 'layout-grid';
//$posts = get_posts(array('numberposts' => 8));
$EditorialClass = 'clear';
@include('header.php');
$switchQuery = Editorial::prepareQuery($EditorialId == 'layout-list' ? 'grid' : 'list', true, $EditorialId == 'layout-list' ? 'list' : 'grid');

?>

<div class="content clear" role="main">
	<article id="single">
		<h1><?php single_cat_title(); ?></h1>
		<section id="layout" class="clear">
			<p><?php _e('Select layout option', 'Editorial'); ?></p>
			<ul class="switch">
				<li<?php echo $EditorialId == 'layout-list' ? ' class="selected"' : ''; ?>><a href="?<?php echo $switchQuery; ?>" class="list"><?php _e('List', 'Editorial'); ?></a></li>
				<li<?php echo $EditorialId == 'layout-grid' ? ' class="selected"' : ''; ?>><a href="?<?php echo $switchQuery; ?>" class="grid"><?php _e('Grid', 'Editorial'); ?></a></li>
			</ul>
		</section>
	</article>
	<?php
	
	function output()
	{
	    global $category;
    	$editorialPage = get_query_var('page') ? get_query_var('page') : 1;
    	
    	$editorialPerPage = 8;
    	
    	query_posts(array('paged' => $editorialPage, 'posts_per_page' => $editorialPerPage, 'cat' => $category->cat_ID));
    
    	if (have_posts())
    	{
    		$i = 1;
    		$section = false;
    		while (have_posts())
    		{
    		    // start section
    		    if (($i-1) % 4 == 0)
    		    {
    		        $section = true;
    		        echo '<section class="featured">';
    		    }
    			the_post();
    			$thumbId = get_post_thumbnail_id();
    			include('featured-article.php');
    			// end section
    			if ($i % 4 == 0)
    			{
    			    $section = false;
    			    echo '</section>';
    			}
    			$i++;
    		}
    		if ($section)
    		{
    		    // close a previously opened section
    		    echo '</section>';
    		}
    	}
    	
    	global $wp_query;
    	if ($editorialPage < $wp_query->max_num_pages)
    	{
    	    // we've got more paging to do
    	    printf(
        	    '<section id="paging">
                    <p><strong>%d / %d</strong> - %s</p>
                    <p class="more"><a href="?%s">%s</a></p>
                </section>',
    	        $editorialPage*$editorialPerPage,
    	        $wp_query->found_posts,
    	        __('articles displayed', 'Editorial'),
    	        Editorial::prepareQuery('page', $editorialPage+1),
    	        __('Display older articles ...', 'Editorial')
    	    );
    	}
    	
    	// no posts in this category?
    	if ($wp_query->max_num_pages == 0)
    	{
    	    Editorial::noResults();
    	}
	}
	
	output();

	?>
</div>
<?php @include('footer.php'); ?>