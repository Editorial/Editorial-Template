<?php

get_header();
the_post();

?>

<div class="content" role="main">
	<article class="main default hentry">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				$success = false;
				if(isset($_POST['trial_email'])) {
					$Trial = new Trial();
					try {
						$Trial->insert($_POST['trial_email']);
						echo '<div class="trial-success">Thank you for applying!</div>';
						$success = true;
					} catch(Exception $e) {
						echo '<div class="trial-error">' . $e->getMessage() . '</div>';
					}
				}
				if($success == false) {
			?>
				<div class="trial-form">
					<form action="/trial/" method="post">
						<p><input type="email" name="trial_email"><input type="submit" value="Apply"></p>
					</form>
				</div>
			<?php } ?>
		</div>
		<footer class="v-hidden">
			<time class="published" datetime="2011-10-20T20:00:00+01:00">10/20/2011</time>
			<a class="author include" href="#editorial">Editorial</a>
		</footer>
	</article>
</div>

<?php get_footer(); ?>
