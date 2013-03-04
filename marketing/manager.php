<?php
/**
 * Template Name: DomainManager
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

require_once 'library/Account.php';
require_once 'library/Domain.php';

$errors = array();

// how did you get here?
if ( false === array_key_exists('hash', $_GET) && !isset($_GET['debug']) )
{
	$errors[] = '404';
}
else
{
	$Account = new Account();
	// invalid hash
	if ( null === $account = $Account->findByHash($_GET['hash']) )
	{
		$errors[] = '404';
	}
	else
	{
		$Domain  = new Domain();

		// update them
		if ( count($_POST) )
		{
			$domains = array();
			// filter them out
			foreach ( $_POST as $k => $v )
			{
				if ( 0 === strpos($k, 'domain-') )
				{
					$domains[] = $v;
					if ( filter_var($v, FILTER_VALIDATE_URL) === false )
					{
						$errors[] = 'domain';
						$errors[] = $k;
					}
				}
			}
			if ( !count($errors) )
			{
				// black magic
				$Domain->manageForAccount($account['account_id'], $domains, true);
				// reload
				Util::redirect('/manager/?hash=' . $_GET['hash']);
			}
		}
		// regular load
		else
		{
			$domains = $Domain->findForAccount($account['account_id']);
		}
	}

}

get_header(); ?>

<div class="content" role="main">
<?php
	if ( in_array('404', $errors) )
	{
?>
    <article class="main default hentry">
        <h1 class="entry-title"><em>Error</em> 404</h1>
        <p class="lead entry-summary">You seem to have lost you way around here.</p>
    </article>
<?php
	}
	else
	{
?>
<article class="content" role="main">
	<section class="process domains-no">
		<h1><em>Licenses</em> &amp; domains</h1>
		<figure class="ribbon">
			<h2 id="domains-no"><?php echo count($domains); ?></h2>
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/bgr/ribbon.png" alt="Ribbon">
		</figure>
	</section>
	<section class="order manage">
		<h3 class="edit">Edit domains <span>for your license/s.</span></h3>
		<?php
		if (count($errors))
		{
			echo '<section class="message errors">
					<h3><span class="v-hidden">Warning</span>!</h3>
					<p class="lead">Please correct following errors:</p>
					<ol>
			';

			if (in_array('domain', $errors))
			{
				echo '<li>Enter a domain name e.g. http://domain.com</li>';
			}

			echo '</ol></section>';
    	}
		?>

		<form id="manage-form" method="post" action="/manager/?hash=<?php echo $_GET['hash']; ?>">
			<fieldset>
				<legend class="v-hidden">Domains</legend>
				<ol id="domains">
				<?php
					foreach ( $domains as $i => $domain )
					{
						echo sprintf('
							<li%s>
								<label for="domain-%d">Domain %2$d</label>
								<input type="text" value="%s" name="domain-%2$d" id="domain-%2$d">
							</li>
						',
							in_array('domain-'.($i+1), $errors) ? ' class="error"' : '',
							$i+1,
							empty($domain->name) ? $domain : $domain->name
						);
					}
				?>
				</ol>
				<div class="info">
					<p>Every issued copy of the theme is licenced to a single domain.<br>
					See our <a href="/documentation/">FAQ</a> for more information.</p>
				</div>
			</fieldset>
			<fieldset class="submit">
				<input type="submit" class="go" value="Save changes">
			</fieldset>
		</form>
	</section>
</article>
<?php
	} // if ( in_array('404', $errors) )
?>
</div>

<?php get_footer(); ?>