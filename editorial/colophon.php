<?php
/*
 * Template Name: Colophon
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */

// id depends on the type of the first posts image
$EditorialId = 'colophon';
$EditorialClass = 'clear';
@include('header.php');
the_post();

// load authors
$authors = Editorial::getOption('authors');
if (is_array($authors) && count($authors))
{
	$userSearch = new WP_User_Query(array('include' => array_keys($authors)));
	$userResults = $userSearch->get_results();

	// complete user data
	foreach ($authors as $key => $value)
	{
		// find in user results
		foreach ($userResults as $User)
		{
			if ($User->ID == $key)
			{
				// found our author
				$User->editorial_role = $value;
				$authors[$key] = $User;
			}
		}
	}
}
else
{
    $authors = array();
}

?>

<div class="content clear" role="main">
	<article id="common" class="hentry">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<footer class="v-hidden">
			<time class="published" datetime="<?php echo date('Y-m-dTH:i', strtotime($post->post_date)); ?>">
				<span class="value-title" title="<?php echo date('Y-m-dTH:i', strtotime($post->post_date)); ?>"> </span>
				<?php the_time(get_option('date_format')); ?>
			</time>
			<em>Written by <a class="author include" href="#editorial">Editorial</a></em>
		</footer>
		<section class="entry-content">
			<?php echo the_content(); ?>
		</section>
		<aside role="complementary">
			<ul id="team">
<?php
				foreach ($authors as $Author)
				{
					$gravatar = sprintf(
						'http://www.gravatar.com/avatar/%s?d=%s&s=%d',
						md5(strtolower(trim($Author->user_email))),
						urlencode(get_bloginfo('template_directory').'/assets/images/_temp/your-name.jpg'), // default image if user has no gravatar
						116
					);
					printf('
				<li class="vcard">
					<figure>
						<img src="%1$s" class="photo" width="116" height="116" alt="%6$s">
						<figcaption>
							<em class="title">%3$s</em>
							<!--strong class="fn n"><a href="%5$s" title="%6$s">%2$s</a></strong-->
							<strong class="fn n">%2$s</strong>
							<a href="mailto:%4$s" class="email">%4$s</a>
						</figcaption>
					</figure>
				</li>
',
						$gravatar,
						$Author->display_name,
						$Author->editorial_role,
						$Author->user_email,
						get_author_posts_url($Author->ID),
						esc_attr($Author->display_name)
					);
				}
?>
			</ul>
		</aside>
	</article>
</div>

<?php @include('footer.php'); ?>