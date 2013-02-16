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
					$Trial->insert($_POST['trial_email'], isset($_POST['t-newsletter']) && $_POST['t-newsletter'] == 'on');
					echo '			<h2 class="trial-success">Thank you for applying!</h2>';
					$success = true;
				} catch(Exception $e) {
					echo '			<p class="trial-error">' . $e->getMessage() . '</p>';
				}
			}
			if($success == false) {
?>
			<form class="trial-form" action="/trial/" method="post">
				<fieldset class="get-trial">
					<label for="trial_email">Your e-mail address</label>
					<input type="email" name="trial_email" id="trial_email">
				</fieldset>
				<fieldset class="subscribe">
					<input type="checkbox" name="t-newsletter" id="t-newsletter">
					<label for="t-newsletter">Subscribe to Editorial newsletter and be the first to find about
					special offers, tips & tricks and updates.</label>
				</fieldset>
				<input type="submit" class="submit" value="Start free trial">
			</form>
<?php } ?>
		</div>
	</section>
</div>

<?php get_footer(); ?>
