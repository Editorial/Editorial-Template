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
require_once 'library/Promo.php';
require_once 'library/Util.php';

session_start();

$errors     = array();
$licences   = 1;
$domains    = array('http://');
$agree      = false;
$newsletter = false;

// promo
if (array_key_exists('promo', $_GET))
{
    $Promo = new Promo();
    $discount = $Promo->getDiscount($_GET['promo']);
    printf(
        '{"discount": %d, "price":%.2f}', 
        $discount, 
        LICENCE_COST * (1 - $discount/100)
    );
    return;
}

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
        unset($_SESSION['errors']);
    }
    if (array_key_exists('agree', $_SESSION))
    {
        $agree = $_SESSION['agree'];
    }
    if (array_key_exists('newsletter', $_SESSION))
    {
        $newsletter = $_SESSION['newsletter'];
    }
    if (array_key_exists('promo', $_SESSION))
    {
        $promo = $_SESSION['promo'];
    }
}

// paypal FUBAR
if ( isset($_GET['paypal']) && isset($_GET['token']) )
{
	$Purchase = new Purchase();
	$purchase = $Purchase->findByExtId($_GET['token']);
	if ( false === is_array($purchase) )
	{
		$errors[] = '404';
	}
	else
	{
		$domains    = json_decode($purchase['domains'], true);
		$licences   = count($domains);
		$newsletter = (bool)$purchase['newsletter'];
		$errors[]   = 'paypal';
	}

	// free it
	unset($purchase, $Purchase);
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

	// newsletter?
	$newsletter = array_key_exists('newsletter', $_POST);
	
	// promo & discount
	$Promo = new Promo();
	$promo = array_key_exists('promo', $_POST) ? $_POST['promo'] : '';
	$discount = array_key_exists('promo', $_POST) ? $Promo->getDiscount($_POST['promo']) : 0;
	if (array_key_exists('promo', $_POST) && strlen($_POST['promo']) && $discount == 0)
	{
	    // invalid promo code entere -> must show to user before they go to paypal
	    $errors[] = 'promo';
	}

    // lets get down to business
    if (!count($errors))
    {
        // do paypal
        try
        {
            $Paypal = new Paypal(PAYPAL_USER, PAYPAL_PASSWORD, PAYPAL_SIGNATURE, PAYPAL_ENDPOINT);
			$licenceCost = LICENCE_COST * (1 - $discount/100);
			// got discount, then use the code -> not ideal but can live with this
			if ($discount > 0)
			{
			    $Promo->useDiscount($_POST['promo']);
			}
			$amount = $licences * $licenceCost;
			$details = $Paypal->setExpressCheckout(
				$amount,
				$licences,
				$licenceCost,
				'Editorial template',
				PAYPAL_CONFIRM_URL,
				PAYPAL_CANCEL_URL
			);
            debug(print_r($details, true));
            // insert payment
            $Purchase = new Purchase();
            $Purchase->insert(array(
                'ext_id'     => $Paypal->getToken(),
                'domains'    => json_encode($domains),
                'amount'     => $amount,
                'discount'   => $discount,
                'date'       => date('Y-m-d H:i:s'),
                'status'     => Purchase::STATUS_STARTED,
                'type'       => Purchase::TYPE_PAYPAL,
                'status'     => Purchase::STATUS_STARTED,
                'newsletter' => (int)$newsletter,
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
			'licences'   => $licences,
			'domains'    => $domains,
			'errors'     => $errors,
			'agree'      => $agree,
			'newsletter' => $newsletter,
            'promo'      => $promo,
        );
        header('Location: /purchase/');
        exit();
    }
}

get_header();

?>

<div class="content" role="main">
<?php
	if ( in_array('404', $errors) )
	{
?>
    <article class="main default hentry">
        <h1 class="entry-title"><em>Error</em> 404</h1>
        <p class="lead entry-summary">You seem to have lost you way around here.</p>
    </article>
</div>
<?php
		get_footer();
		exit;
	}
?>
	<section class="process">
		<header>
			<ol class="step1">
				<li id="step1" class="selected">Place order</li>
				<li id="step2">Transaction</li>
				<li id="step3">Download</li>
			</ol>
			<h1><em>Place</em> Order</h1>
		</header>
		<figure class="tablets">
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/tablets.png" alt="Tablets">
		</figure>
	</section>
	<section class="order">
<?php

    	if (count($errors))
    	{
			echo '<section class="message errors">';

			if ( in_array('cancel', $errors) )
			{
				echo '
					<h3><span class="v-hidden">Warning</span>!</h3>
					<p class="lead">Your purchase was canceled.</p>
					<p>Shame, we were just starting to get along. If you canceled by mistake you
					can return to paypal by clicking "Proceed to checkout" button again.</p>';
			}
			elseif ( in_array('paypal', $errors) )
			{
				echo '
					<h3><span class="v-hidden">Wo-ou, something went terribly wrong.</span>!</h3>
					<p class="lead">
						Most likely there was a problem with PayPal connection. We sincerely
						apologise for the inconvenience. Please try again or
						<a href="">contact our support team</a> to help you resolve this issue.
					</p>';
			}
			else
			{
				echo '
					<h3><span class="v-hidden">Warning</span>!</h3>
					<p class="lead">Please correct following errors:</p>
					<ol>
				';

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
				
				if (in_array('promo', $errors))
				{
					echo '<li>The promo code entered is invalid. <a href="/about/">Contact us</a> if you think the code should still be valid.</li>';
				}
				
				echo '</ol>';
			}
			echo '</section>';
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
					See our <a href="/faq/" target="_blank">FAQ</a> for more information.</p>
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
			<fieldset class="domain promo">
                <legend class="v-hidden">Discount voucher</legend>
                <div class="info">
                    <h3>Discount voucher</h3>
                    <p>Sometimes we give them out to good children.</p>
                </div>
                <div class="code">
                    <label for="promo">Code</label><input type="text" value="<?php echo $promo; ?>" name="promo" id="promo">
                </div>
            </fieldset>
			<fieldset class="tearms">
				<legend class="v-hidden">Newsletter</legend>
				<div class="info">
					<h3>Subscribe to our newsletter</h3>
				</div>
				<div class="i-agree">
					<input type="checkbox" value="yes" name="newsletter" id="newsletter"<?php echo $newsletter ? ' checked' : ''; ?>>
					<label for="newsletter">Check to be the first in line to find about price drops, news and more.</label>
				</div>
			</fieldset>
			<fieldset class="tearms">
				<legend class="v-hidden">Tearms</legend>
				<div class="info">
					<h3>Terms of use</h3>
				</div>
				<div class="i-agree">
					<input type="checkbox" value="yes" name="i-agree" id="i-agree"<?php echo $agree ? ' checked' : ''; ?>>
					<label for="i-agree">I have read and agree with <a href="/" target="_blank">Terms of use</a> &amp; <a href="/" target="_blank">Privacy policy</a>.</label>
				</div>
			</fieldset>
			<fieldset class="submit">
				<div class="paypal-info">
					<figure class="verified">
						<a href="https://www.paypal.com/verified/pal=natan@editorialtemplate.com" target="_blank">
							<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/paypalverified.gif" width="60" height="60" alt="Paypal verified">
						</a>
					</figure>
					<p>Clicking “Proceed to checkout” will take you to the PayPal Website.<br>
					After confirming your order you will be returned to our website to complete your purchase.</p>
				</div>
				<div class="loader">
					<input type="submit" id="checkout" class="go" value="Proceed to checkout">
				</div>
			</fieldset>
		</form>
	</section>
</div>

<?php get_footer(); ?>