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
	require 'db_connection.php';

	Class Functions extends DbConn{
		//send email to the user
		private function sendEmail($email, $r_key){
			//Create an instance; passing `true` enables exceptions
			$mail = new PHPMailer(true);

			try{
			    //Server settings
			    $mail->isSMTP();                                            //Send using SMTP
			    $mail->Host       = 'smtp.hostinger.com';                       //Set the SMTP server to send through
			    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			    $mail->Username   = 'bigaadocrequest@bigaadocrequest.online';  //SMTP username
			    $mail->Password   = 'BigaaDocRequest2021';                  //SMTP password
			    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable implicit TLS encryption
			    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			    //Recipients
			    $mail->setFrom('bigaadocrequest@bigaadocrequest.online', 'Bigaa Certificate Request');
			    $mail->addAddress($email);     //Add a recipient

			    //Content
			    $mail->isHTML(true);                                  //Set email format to HTML
			    $mail->Subject = 'Reset Password';
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
				              <h5 class="text-center" style="margin-top: 10px;">Bigaa Online Certificate Request</h5>
				            </div>
				        </div>
				        <div class="row justify-content-center mt-5">
				        	<h4 class="text-success text-center" style="margin: 30px 0 10px 0">Reset Password</h4>
				        </div>
				        <div class="row justify-content-center">
				        	<div class="col-6">
				        		<p class="text-center mt-3" style="margin-bottom: 30px;">Hello, you have requested to reset your password in your account. Please do not share the link attached in this email.</p>
				        	</div>
				        </div>
				        <div class="row" style="display: flex; justify-content: center!important;">
				        	<a href="'."https://".$_SERVER['SERVER_NAME']."/authenticate/reset_password.php?key=".$r_key.'" target="_blank" style="display: block; margin: 0 auto; font-size: 18px; padding: 8px 15px; border-radius: 5px; border: none; background: #28a745!important; color: white; font-weight: 500; text-decoration: none!important;">Reset Password</a>
				        </div>
				        <div class="row justify-content-center mt-4">
				        	<div class="col-6">
				        		<p class="text-center mt-3" style="margin-top: 30px;">Click the button above to reset your password. If you are having trouble clicking the button, click the link below instead: </p>
				        	</div>
				        </div>
				        <div class="row" style="display: flex; justify-content: center!important;">
				        	<a href="'."https://".$_SERVER['SERVER_NAME']."/authenticate/reset_password.php?key=".$r_key.'" target="_blank" style="display: block; margin: 0 auto; font-size: 15px; margin-top: 20px; text-decoration: underline;">'."https://".$_SERVER['SERVER_NAME']."/authenticate/reset_password.php?key=".$r_key.'</a>
				        </div>
				        <div class="row justify-content-center mt-4">
				        	<div class="col-6">
				        		<p class="text-center mt-3" style="margin-top: 30px;"><b>If you did not request for a password reset, kindly ignore this message. Thank you!</p>
				        	</div>
				        </div>
				      </div>
				  </body>
			    ';
			    if($mail->send())
			    	return 'SUCCESS_REQUEST_RESET';
			    else
			    	return 'MAILER_ERROR';
			} 
			catch (Exception $e){
			    return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}

		//validate if email is existing and verified
		public function isEmailExist($email){
			try{
				$sql = "SELECT resident_login_info.email FROM resident_login_info INNER JOIN verification ON resident_login_info.resident_id = verification.resident_id WHERE resident_login_info.email = ? AND verification.isEmailVerified = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$email, 1])){
					$result = $stmt->fetch();

					if($result){
						return 1;
					}
					else{
						return 0;
					}
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}

		//insert reset password request on database
		public function addResetPasswordRequest($email){
			try{
				date_default_timezone_set('Asia/Manila');
			    $date_today = date('Y-m-d H:i:s');

				//select the resident id of the requesting email
				$sql = "SELECT resident_id FROM resident_login_info WHERE email = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$email])){
					$res_id = $stmt->fetch()['resident_id'];

					//insert new reset password request on database
					$r_key = md5(time());
					$sql2 = "INSERT INTO reset_password_request (resident_id, reset_key, date_requested) VALUES (?,?,?) ON DUPLICATE KEY UPDATE reset_key = ?";
					$stmt2 = $this->connect()->prepare($sql2);
					if($stmt2->execute([$res_id, $r_key, $date_today, $r_key]))
						return $this->sendEmail($email, $r_key);
					else
						throw new Exception('INSERT_SQL_ERROR');
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//execute functions
	if(isset($_POST['checkEmail'])){
		$checkEmail = new Functions();
		echo $checkEmail->isEmailExist($_POST['checkEmail']);
		unset($checkEmail);
	}

	if(isset($_POST['resetEmail'])){
		$resetEmail = new Functions();
		echo $resetEmail->addResetPasswordRequest($_POST['resetEmail']);
		unset($resetEmail);
	}
?>