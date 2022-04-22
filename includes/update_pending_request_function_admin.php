<?php
	//Import PHPMailer classes into the global namespace
	//These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	//Load Composer's autoloader
	require '../mailer/Exception.php';
	require '../mailer/PHPMailer.php';
	require '../mailer/SMTP.php';

	require 'TwillioSMS_API/vendor/autoload.php';
	use Twilio\Rest\Client;

	require 'db_connection.php';

	Class Functions extends DbConn{
		//send sms to the user
		private function sendSMS($mobileno){
			$mobile_with_countryCode = substr_replace($mobileno, '+63', 0, 1);
			// Your Account SID and Auth Token from twilio.com/console
			$account_sid = 'AC22f158aefffe98daeb8c5da8aa5e9024';
			$auth_token = '2c1a9a26315a55c9ad5622b39e80bb55';
			// In production, these should be environment variables. E.g.:
			// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

			// A Twilio number you own with SMS capabilities
			$twilio_number = "+16815785474";

			$client = new Client($account_sid, $auth_token);

			if($client->messages->create(
			    // Where to send a text message (your cell phone?)
			    $mobile_with_countryCode,
			    array(
			        'from' => $twilio_number,
			        'body' => "Bigaa Online Certificate Request\n\nYour request is declined, please make sure that your PURPOSE is CORRECT and VALID, kindly send a request again with correct and valid purpose. Thank you."
			    )
			))
				return 1;
			else
				return 0;
		}

		//send email to the user
		private function sendEmail($email){
			//Create an instance; passing `true` enables exceptions
			$mail = new PHPMailer(true);

			try {
			    //Server settings
			    $mail->isSMTP();                                            //Send using SMTP
			    $mail->Host       = 'smtp.hostinger.com';                       //Set the SMTP server to send through
			    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			    $mail->Username   = 'bigaadocrequest@bigaadocrequest.online';            //SMTP username
			    $mail->Password   = 'BigaaDocRequest2021';                  //SMTP password
			    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable implicit TLS encryption
			    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			    //Recipients
			    $mail->setFrom('bigaadocrequest@bigaadocrequest.online', 'Bigaa Certificate Request');
			    $mail->addAddress($email);     //Add a recipient

			    //Content
			    $mail->isHTML(true);                                  //Set email format to HTML
			    $mail->Subject = 'Request Declined';
			    $mail->Body    = '
						  <head>
						    <style>
						    	*{
						    		font-family: "Arial";
						    	}
						    	.wrapper{background-color: whitesmoke;}
						    	.justify-content-center{justify-content: center;}
						    	.text-center{text-align: center;}
						    	p{font-size: 14px;}
						    	h4{font-size: 24px}
						    	h5{font-size: 18px;}
						    	.text-success{color: #28a745!important;}
						    	a:hover{
						    		cursor: pointer;
						    	}
						    </style>
						  </head>
						  <body>
						      <div class="wrapper" style="padding: 50px 35px;">
						      	<div class="row">
						            <img src="https://i.ibb.co/Jdxqv7w/logo-bigaa-header.png" width="120" height="120" style="display: block; margin: 0 auto;">
						      	</div>
						        <div class="row justify-content-center" style="margin-top: 25px;">
						            <div>
						              <h4 class="text-center" style="margin-bottom: 10px;">Brgy. Bigaa, Cabuyao City</h4>
						              <h5 class="text-center" style="margin-top: 10px;">Bigaa Document Request</h5>
						            </div>
						        </div>
						        <div class="row justify-content-center mt-5">
						        	<h4 class="text-success text-center" style="margin: 30px 0 10px 0">Request declined</h4>
						        </div>
						        <div class="row justify-content-center">
						        	<div class="col-6">
						        		<p class="text-center mt-3" style="margin-bottom: 30px;">Your request is declined, please make sure that your PURPOSE is CORRECT and VALID, kindly send a request again with correct and valid purpose. Thank you.</p>
						        	</div>
						        </div>
						      </div>
						  </body>
			    ';

			    if($mail->send())
			    	return 1;
			    else
			    	return 0;
			} 
			catch (Exception $e){
			    return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}

		//change request status in request (Admin)
		//approving request
		public function updatePendingStatus($status, $id){
			try{
				//fetch the username of the current session
				$admin_name = '';
				$sql = 'SELECT admin_user FROM admin_info WHERE admin_id = ? LIMIT 1';
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$_SESSION['ADMIN_ID']]))
					$admin_name = $stmt->fetch()['admin_user'];
				else
					throw new Exception('SQL_ERROR');

				$sql2 = "UPDATE requests_list SET request_status = ?, remarks = ?, admin_processed = ? WHERE request_id = ?";
				$stmt2 = $this->connect()->prepare($sql2);
				if($stmt2->execute([$status, 'Your request is being processed', $admin_name, $id]))
					return 'SUCCESS_UPDATE_REQ';
				else
					throw new Exception('UPDATE_SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}

		//change request status in request with remarks (Admin)
		//declining request
		public function updatePendingStatusRemarks($status, $id, $remarks){
			try{
				//fetch the username of the current session
				$admin_name = '';
				$sql4 = 'SELECT admin_user FROM admin_info WHERE admin_id = ? LIMIT 1';
				$stmt4 = $this->connect()->prepare($sql4);
				if($stmt4->execute([$_SESSION['ADMIN_ID']]))
					$admin_name = $stmt4->fetch()['admin_user'];
				else
					throw new Exception('SQL_ERROR');

				$sql = "UPDATE requests_list SET request_status = ?, remarks = ?, admin_processed = ? WHERE request_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$status, $remarks, $admin_name, $id])){
					//fetch email and mobile to send notif
					$email = '';
					$mobile = '';
					$sql2 = "SELECT resident_login_info.email, resident_info.mobile_number FROM resident_login_info INNER JOIN requests_list ON resident_login_info.resident_id = requests_list.resident_id INNER JOIN resident_info ON resident_info.resident_id = requests_list.resident_id WHERE requests_list.request_id = ? LIMIT 1";
					$stmt2 = $this->connect()->prepare($sql2);
					if($stmt2->execute([$id])){
						//get results
						foreach ($stmt2->fetchAll() as $rows) {
							$email = $rows['email'];
							$mobile = $rows['mobile_number'];
						}
						
						//check if mobile number is verified
						$sql3 = "SELECT verification.isMobileVerified FROM verification WHERE resident_id = ?";
						$stmt3 = $this->connect()->prepare($sql3);
						if($stmt3->execute([$_SESSION['ID']])){
						   $isMobileVerified = 0;
						   
						   foreach ($stmt3->fetchAll() as $rows){
						       $isMobileVerified = $rows['isMobileVerified'];
						   }
						   
						   if($isMobileVerified == 1){
                                //send email and sms
        						if($this->sendEmail($email) == 1){
        							if($this->sendSMS($mobile) == 1)
        								return 'SUCCESS_UPDATE_REQ';
        							else
        								return 'EMAIL_ONLY_SENT';
        						}
        						else if($this->sendSMS($mobile) == 1){
        							if($this->sendEmail($email) == 1)
        								return 'SUCCESS_UPDATE_REQ';
        							else
        								return 'SMS_ONLY_SENT';
    					    	}
						   }
						   else if($isMobileVerified == 0){
    					       //send email only
        						if($this->sendEmail($email) == 1){
        							return 'SUCCESS_UPDATE_REQ';
        						}
        						else{
        						    return 'FAILED_SEND';
        						}
						   }
						}
						else
						    throw new Exception('FAILED_SEND');
					}
					else
						throw new Exception('FAILED_SEND');
				}
				else
					throw new Exception('UPDATE_SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//update request status for pending request (Admin)
	if(isset($_POST['status']) && isset($_POST['req_id'])){
		$updateRequest = new Functions();
		echo $updateRequest->updatePendingStatus($_POST['status'], $_POST['req_id']);
		unset($updateRequest);
	}

	//decline request status for pending request with remarks (Admin)
	if(isset($_POST['status_decline']) && isset($_POST['req_id_decline']) && isset($_POST['remarks_decline'])){
		$updateRequestDecline = new Functions();
		echo $updateRequestDecline->updatePendingStatusRemarks($_POST['status_decline'], $_POST['req_id_decline'], $_POST['remarks_decline']);
		unset($updateRequestDecline);
	}
?>