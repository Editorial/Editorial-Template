<?php
/**
 * Paypal
 *
 * @package     PicasIcons
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2010, ThirdFrameStudios
 * @link        http://www.thirdframestudios.com
 * @version     1.0
 */
class Paypal
{
    /**
     * Paypal params
     *
     * @var array
     */
    private $_params;

    /**
     * Construct
     *
     * @return Paypal
     * @author Miha Hribar
     */
    public function __construct($username, $password, $signature, $endpoint)
    {
        $this->_params = array(
            'username'  => $username,
            'password'  => $password,
            'signature' => $signature,
            'endpoint'  => $endpoint,
        );
    }

    /**
     * Get token if set
     *
     * @return string
     * @author Miha Hribar
     */
    public function getToken()
    {
        return isset($this->_params['token']) ? $this->_params['token'] : '';
    }

    /**
     * Set Express Checkout
     *
     * @param  float $total
     * @param  string $confirmUrl
     * @param  string $cancelUrl
     * @return void
     * @author Miha Hribar
     */
    public function setExpressCheckout($total, $confirmUrl, $cancelUrl)
    {
        $this->_params['method'] = 'SetExpressCheckout';
        // Set the request as a POST FIELD for curl.
        $additional = sprintf(
            '&Amt=%s&ReturnUrl=%s&CANCELURL=%s&PAYMENTACTION=%s&CURRENCYCODE=%s',
            urlencode($total),
            urlencode($confirmUrl),
            urlencode($cancelUrl),
            'Autorization',
            'USD'
        );
        return $this->_makeRequest($additional);
    }

    /**
     * Get Express Checkout
     *
     * @param  string $token
     * @return array
     * @author Miha Hribar
     */
    public function getExpressCheckout($token)
    {
        $this->_params['method'] = 'GetExpressCheckoutDetails';
        $additional = '&TOKEN='.urlencode(htmlspecialchars($token));
        return $this->_makeRequest($additional);
    }

    /**
     * Do Express Checkout
     *
     * @param  string $token
     * @param  string $payerId
     * @param  float $total
     * @return array
     * @author Miha Hribar
     */
    public function doExpressCheckout($token, $payerId, $total)
    {
        $this->_params['method'] = 'DoExpressCheckoutPayment';
        $additional = sprintf(
            '&TOKEN=%s&PAYERID=%s&PAYMENTACTION=%s&AMT=%s&CURRENCYCODE=%s',
            urlencode($token),
            urlencode($payerId),
            'Sale',
            urlencode($total),
            'USD'
        );
        return $this->_makeRequest($additional);
    }

    /**
     * Validate IPN call. If no exception is thrown the IPN is valid.
     *
     * @param  array $request
     * @param  string $endpoint
     * @return void
     * @author Miha Hribar
     * @throws Paypl_Exception
     */
    public function validateIPN($request, $endpoint)
    {
        // send request back to paypal for validation
        $req = 'cmd=_notify-validate';
        foreach ($_POST as $key => $value)
        {
            $req .= sprintf('&%s=%s', $key, urlencode(stripslashes($value)));
        }
        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if(!$httpResponse)
        {
            throw new Paypal_Exception($this->_params['method']." failed: ".curl_error($ch).'('.curl_errno($ch).')');
        }
        // check response
        if($httpResponse != 'VERIFIED')
        {
            throw new Paypal_Exception(sprintf('Got unexpecdet response from paypal: %s', $httpResponse));
        }
    }

    /**
     * Returns Paypal Express checkout url
     *
     * @return string
     * @author Miha Hribar
     */
    public function getPaypalExpressCheckoutURL()
    {
        return sprintf(PAYPAL_URL, $this->_params['token']);
    }

    /**
     * Make request to NVP server
     *
     * @return string
     * @author Miha Hribar
     */
    private function _makeRequest($additional)
    {
        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_params['endpoint']);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        Log::trace($this->_getNVPString($additional));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_getNVPString($additional));

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if(!$httpResponse)
        {
            throw new Paypal_Exception($this->_params['method']." failed: ".curl_error($ch).'('.curl_errno($ch).')');
        }

        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value)
        {
            $tmpAr = explode("=", $value);
            if(sizeof($tmpAr) > 1)
            {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
        {
            throw new Paypal_Exception("Invalid HTTP Response for POST request($this->_getNVPString($additional)).");
        }

        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {
            // Redirect to paypal.com.
            $this->_params['token'] = urldecode($httpParsedResponseAr["TOKEN"]);
            return $httpParsedResponseAr;
        }
        else
        {
            throw new Paypal_Exception('SetExpressCheckout failed: ' . print_r($httpParsedResponseAr, true));
        }
    }

    /**
     * Return NVP string for current request
     *
     * @param  string $additional
     * @return string
     * @author Miha Hribar
     */
    private function _getNVPString($additional = '')
    {
        return sprintf(
           'METHOD=%s&VERSION=%s&PWD=%s&USER=%s&SIGNATURE=%s%s',
            $this->_params['method'],
            urlencode(PAYPAL_VERSION),
            urlencode(PAYPAL_PASSWORD),
            urlencode(PAYPAL_USER),
            urlencode(PAYPAL_SIGNATURE),
            $additional
        );
    }
}

/**
 * Paypal exception
 *
 * @package     PicasIcons
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2010, ThirdFrameStudios
 * @link        http://www.thirdframestudios.com
 * @version     1.0
 */
class Paypal_Exception extends Exception
{
    /**
     * Construct
     *
     * @return void
     * @author Miha Hribar
     */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
        // log message
        Log::error($message);
    }
}
