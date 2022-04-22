<?php
	require 'TwillioSMS_API/vendor/autoload.php';
	use Twilio\Rest\Client;


	function sendSMS($mobileno, $req_no, $docType){
		$mobile_with_countryCode = substr_replace($mobileno, '+63', 0, 1);
		// Your Account SID and Auth Token from twilio.com/console
		$account_sid = 'AC22f158aefffe98daeb8c5da8aa5e9024';
		$auth_token = '2c1a9a26315a55c9ad5622b39e80bb55';
		// In production, these should be environment variables. E.g.:
		// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

		// A Twilio number you own with SMS capabilities
		$twilio_number = "+16815785474";

		$client = new Client($account_sid, $auth_token);
		$additionalMessage = $docType == 'Barangay Clearance' ? ' please bring ONE (1) 1x1 picture for your '.$docType.',' : '';

		if($client->messages->create(
		    // Where to send a text message (your cell phone?)
		    $mobile_with_countryCode,
		    array(
		        'from' => $twilio_number,
		        'body' => "Bigaa Online Certificate Request\n\nYour $docType is ready to pickup,$additionalMessage your REQUEST NUMBER is: $req_no"
		    )
		))
			return 1;
		else
			return 0;
	}
?>
