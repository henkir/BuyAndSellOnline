<?php
/****************************************************
constants.php

This is the configuration file for the samples.This file
defines the parameters needed to make an API call.
****************************************************/

/**
# API user: The user that is identified as making the call. you can
# also use your own API username that you created on PayPal’s sandbox
# or the PayPal live site
*/

$sandbox = TRUE;

if ($sandbox == TRUE)
{
    define('API_USERNAME', 'robban_1274642016_biz_api1.hotmail.com');
    define('API_PASSWORD', '1274642026');
    define('API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AIhW89.q6lBZucF.z4LsF3w0d7oJ');
    define('API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');
    define('PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
}
else
{
    define('API_USERNAME', 'live_username');
    define('API_PASSWORD', 'live_password');
    define('API_SIGNATURE', 'live_signature');
    define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
    define('PAYPAL_URL', 'https://www.paypal.com/webscr&cmd=_express-checkout&token=');
} 


/**
USE_PROXY: Set this variable to TRUE to route all the API requests through proxy.
like define('USE_PROXY',TRUE);
*/
define('USE_PROXY',FALSE);
/**
PROXY_HOST: Set the host name or the IP address of proxy server.
PROXY_PORT: Set proxy port.

PROXY_HOST and PROXY_PORT will be read only if USE_PROXY is set to TRUE
*/
define('PROXY_HOST', '127.0.0.1');
define('PROXY_PORT', '808');

/* Define the PayPal URL. This is the URL that the buyer is
   first sent to to authorize payment with their paypal account
   change the URL depending if you are testing on the sandbox
   or going to the live PayPal site
   For the sandbox, the URL is
   https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=
   For the live site, the URL is
   https://www.paypal.com/webscr&cmd=_express-checkout&token=
   */


/**
# Version: this is the API version in the request.
# It is a mandatory parameter for each API request.
# The only supported value at this time is 2.3
*/

define('VERSION', '3.0');

?>

