<?php
/**
 * Paypal Direct Payment API Component class file.
 */
App::import('Vendor','paypal' ,array('file'=>'paypal/Paypal.php'));
class PaypalComponent extends Object{

	function processPayment($paymentInfo, $function, $paypalInfo){
		$paypal = new Paypal();
		if ($function=="DoDirectPayment")
			return $paypal->DoDirectPayment($paymentInfo, $paypalInfo);
		elseif ($function=="SetExpressCheckout")
			return $paypal->SetExpressCheckout($paymentInfo);
		elseif ($function=="GetExpressCheckoutDetails")
			return $paypal->GetExpressCheckoutDetails($paymentInfo);
		elseif ($function=="DoExpressCheckoutPayment")
			return $paypal->DoExpressCheckoutPayment($paymentInfo);
		else
			return "Function Does Not Exist!";
	}
}
?>