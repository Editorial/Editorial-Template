<?php

get_header();
the_post();

?>

<div class="content" role="main">
	<section class="process">
		<header>
			<h1><em>Free</em> trial</h1>
		</header>
		<figure class="trial">
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/free-trial.png" alt="Free trial">
		</figure>
	</section>
	<section class="order">
		<div class="info">
			<?php the_content(); ?>
		</div>
		<div class="action">
<?php
			$success = false;
			if(isset($_POST['trial_email'])) {
				$Trial = new Trial();
				try {
					$Trial->insert($_POST['trial_email']);
					echo '			<div class="trial-success">Thank you for applying!</div>';
					$success = true;
				} catch(Exception $e) {
					echo '			<div class="trial-error">' . $e->getMessage() . '</div>';
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
<!--
			<form class="nl-form" method="post">
				<label for="nl-email">Your e-mail address</label>
				<input type="text" name="nl-email" id="nl-email">
				<input type="checkbox" name="nl-newsletter" id="nl-newsletter">
				<label for="nl-newsletter">Subscribe to Editorial newsletter and be the first to find about
				special offers, tips & tricks and updates.</label>
				<input type="submit" value="Start free trial">
			</form>
-->
		</div>
	</section>
</div>

<?php get_footer(); ?>
