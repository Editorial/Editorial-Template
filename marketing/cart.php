<?php
/**
 * Template Name: Cart
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

require_once 'library/Paypal.php';
require_once 'library/Purchase.php';
require_once 'library/Util.php';

session_start();

$errors   = array();
$licences = 1;
$domains  = array('');
$agree    = false;

// cancel purchase
if (array_key_exists('cancel', $_GET))
{
    // unset everything from session
    $_SESSION = array();
    $errors[] = 'cancel';

	// set purchase as cancelled
	if ( array_key_exists('token', $_GET) )
	{
		$Purchase = new Purchase();
		$Purchase->cancel($_GET['token']);
	}
}

// session?
if (isset($_SESSION) && count($_SESSION))
{
    if (array_key_exists('licences', $_SESSION))
    {
        $licences = $_SESSION['licences'];
    }
    if (array_key_exists('domains', $_SESSION))
    {
        $domains = $_SESSION['domains'];
    }
    if (array_key_exists('errors', $_SESSION))
    {
        $errors = $_SESSION['errors'];
    }
    if (array_key_exists('agree', $_SESSION))
    {
        $agree = $_SESSION['agree'];
    }
}

// handle post
if (isset($_POST) && count($_POST))
{
    // reset errors
    $errors = array();

    // check licence
    if (!array_key_exists('licenses-c', $_POST) || !ctype_digit($_POST['licenses-c']) || (int)$_POST['licenses-c'] < 1)
    {
        $errors[] = 'licenses';
    }
    else
    {
        $licences = (int)$_POST['licenses-c'];
        $domains = array_fill(0, $licences, '');
    }

    // check domain
    if (!array_key_exists('domain', $_POST)
        || !is_array($_POST['domain'])
        || !count($_POST['domain'])
        || $licences != count($_POST['domain']))
    {
        $errors[] = 'domain';
    }

    // validate domains
    if (array_key_exists('domain', $_POST) && is_array($_POST['domain']) && count($_POST['domain']))
    {
        $domains = array();
        // check the domains are valid
        foreach ($_POST['domain'] as $key => $domain)
        {
            if ($key+1 > $licences) break;
            $domains[] = $domain;
            if (filter_var($domain, FILTER_VALIDATE_URL) === false)
            {
                $errors[] = 'domain';
                $errors[] = 'domain-'.$key;
            }
        }
    }

    // agree?
    if (!array_key_exists('i-agree', $_POST))
    {
        $errors[] = 'agree';
        $agree = false;
    }
    else
    {
        $agree = true;
    }

    // lets get down to business
    if (!count($errors))
    {
        // do paypal
        try
        {
            $Paypal = new Paypal(PAYPAL_USER, PAYPAL_PASSWORD, PAYPAL_SIGNATURE, PAYPAL_ENDPOINT);
            $amount = $licences*150;
            $details = $Paypal->setExpressCheckout($amount, PAYPAL_CONFIRM_URL, PAYPAL_CANCEL_URL);
            debug(print_r($details, true));
            // insert payment
            $Purchase = new Purchase();
            $Purchase->insert(array(
                'ext_id'   => $Paypal->getToken(),
				'domains'  => json_encode($domains),
                'amount'   => $amount,
				'date'     => date('Y-m-d H:i:s'),
				'status'   => Purchase::STATUS_STARTED,
				'type'     => Purchase::TYPE_PAYPAL,
                'status'   => Purchase::STATUS_STARTED,
            ));
            // redirect to paypal
            Util::redirect($Paypal->getPaypalExpressCheckoutURL());
        }
        catch (Paypal_Exception $e)
        {
            debug('failed');
            Util::redirect('/');
        }
    }
    else
    {
        // add to session and redirect to avoid post errors
        $_SESSION = array(
            'licences' => $licences,
            'domains'  => $domains,
            'errors'   => $errors,
            'agree'    => $agree,
        );
        header('Location: /purchase/');
        exit();
    }
}

get_header(); ?>

<div class="content" role="main">
	<section class="process">
		<header>
			<ol class="step1">
				<li id="step1" class="selected">Place order</li>
				<li id="step2">Transaction</li>
				<li id="step3">Download</li>
			</ol>
			<h1><em>Place</em> Order</h1>
		</header>
		<figure>
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/tablets.png" alt="Tablets">
		</figure>
	</section>
	<section class="order">
    	<?php

    	if (count($errors))
    	{
    	    echo '<section class="message errors">
    	        <h3><span class="v-hidden">Warning</span>!</h3>
                <p class="lead">Please correct following errors:</p>
                <ol>';
    	    if (in_array('licences', $errors))
    	    {
    	        echo '<li>Enter a valid number of desired licences</li>';
    	    }

    	    if (in_array('domain', $errors))
    	    {
    	        echo '<li>Enter a domain name e.g. http://domain.com</li>';
    	    }

    	    if (in_array('agree', $errors))
    	    {
    	        echo '<li>Please read and agree to our <a href="/terms-of-use/" target="_blank">Terms of use</a>.</li>';
    	    }

    	    if (in_array('cancel', $errors))
    	    {
    	        echo '<li>Your purchase was canceled. Shame, we were just starting to get along.</li>';
    	    }

            echo '</ol></section>';
    	}

    	?>
		<form id="buy-form" method="post" action="/purchase/">
			<fieldset class="licenses">
				<legend class="v-hidden">Licenses</legend>
				<ol>
					<li class="item">
						<label for="item">Item</label>
						<input type="text" disabled value="Editorial Wordpress theme" name="item" id="item">
					</li>
					<li class="price-c">
						<label for="price-c">Price</label>
						<input type="text" disabled value="€150" name="price-c" id="price-c">
					</li>
					<li class="licenses-c<?php echo in_array('licences', $errors) ? ' error' : ''; ?>">
						<label for="licenses-c"># of licenses</label>
						<input type="text" value="<?php echo $licences; ?>" name="licenses-c" id="licenses-c">
					</li>
					<li class="total">
						<label for="total">Total</label>
						<input type="text" disabled value="&euro;<?php echo $licences*150; ?>" name="total" id="total">
					</li>
				</ol>
			</fieldset>
			<fieldset class="domain">
				<legend class="v-hidden">Domain</legend>
				<div class="info">
					<h3>Which domain/s will you be using the theme on?</h3>
					<p>Which domain/s will you be using the theme on?
					Every issued copy of the theme is licensed to a single domain.
					But don’t worry, you can change the domain for your license/s anytime.
					See our <a href="/help/" target="_blank">FAQ</a> for more information.</p>
				</div>
				<ol id="domains">
				    <?php

				    foreach ($domains as $key => $domain)
				    {
				        printf('<li%3$s>
                                <label for="domain-%1$d">Domain %1$d</label>
                                <input type="text" name="domain[]" id="domain-%1$d" value="%2$s">
                            </li>',
				            $key+1,
				            $domain,
				            in_array('domain-'.$key, $errors) ? ' class="error"' : ''
				        );
				    }

				    ?>
				</ol>
			</fieldset>
			<fieldset class="payement">
				<legend class="v-hidden">Payement</legend>
				<div class="info">
					<h3>Preferred method of payement:</h3>
				</div>
				<ol class="choose">
					<li>
						<input type="radio" value="paypal" name="payement" id="payment-1" checked>
						<label for="payment-1">Paypal</label>
					</li>
				</ol>
			</fieldset>
			<fieldset class="tearms">
				<legend class="v-hidden">Terms</legend>
				<div class="info">
					<h3>Terms of use</h3>
				</div>
				<div class="i-agree">
					<input type="checkbox" value="yes" name="i-agree" id="i-agree"<?php echo $agree ? ' checked' : ''; ?>>
					<label for="i-agree">I have read and agree with <a href="/terms-of-use/" target="_blank">Terms of use</a>.</label>
				</div>
			</fieldset>
			<fieldset class="submit">
				<input type="submit" class="go" value="Proceed to checkout">
			</fieldset>
		</form>
	</section>
</div>

<?php get_footer(); ?>