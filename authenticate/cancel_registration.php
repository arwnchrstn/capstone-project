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

	try{
		if(!@include '../includes/db_connection.php'){
			throw new Exception('File Error: Unable to load configuration file');
		}
	}
	catch(Exception $e){
		http_response_code(404);
		die($e->getMessage());
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../fontawesome/css/all.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="icon" href="../resources/brgy-logo.png" type="image/x-icon"/>
	<title>Cancel Registration</title>
	<style>
		html{
			min-width: 280px;
			max-width: 2000px;
		}
		#wrapper{
			height: 100vh;
			background-color: rgba(69, 176, 111, 0.3);
		}
	</style>
</head>
<body>
	<?php 
		try{
			if(isset($_GET['key']) && isset($_GET['email'])){
				Class Functions extends DbConn{
					public function execute(){
						//check if vkey and email is valid and existing
						$stmt = $this->connect()->prepare("SELECT verification.verification_key, verification.isEmailVerified, resident_info.resident_id, resident_info.picture_name, resident_info.voters_picture_name FROM verification INNER JOIN resident_info ON verification.resident_id = resident_info.resident_id INNER JOIN resident_login_info ON resident_login_info.resident_id = resident_info.resident_id WHERE verification.verification_key = ? AND resident_login_info.email = ? LIMIT 1");
						$stmt->execute([$_GET['key'], $_GET['email']]);
						$result = $stmt->fetch();

						if($result){
							if($result['isEmailVerified'] == 0){
								//delete the account
								$sql = "DELETE FROM resident_info WHERE resident_id = ? LIMIT 1";
								$stmt2 = $this->connect()->prepare($sql);
								$stmt2->execute([$result['resident_id']]);
								//also deletes the pictures
								unlink('../uploaded images/profile pictures/'.$result['picture_name']);
								unlink('../uploaded images/voters id pictures/'.$result['voters_picture_name']);

								echo '
									<div class="container-fluid" id="wrapper">
										<div class="row justify-content-center">
											<div class="col-md-10 col-12">
												<div class="icon-box" style="margin-top: 75px;">
													<h5 class="text-success text-center m-0" style="font-size: 150px;"><span class="fas fa-check-circle"></span></h5>
												</div>
												<h4 class="text-success text-center">Account Successfully Deleted</h4>
												<h5 class="text-success text-center mt-3">Your registration for Bigaa Online Certificate Request is cancelled. Kindly check your email for confirmation. If you didn\'t receive the email, check it on your span folder. Thank You!</h5>
											</div>
										</div>
									</div>
								';

								//send email to confirm account deletion
								//Create an instance; passing `true` enables exceptions
								$mail = new PHPMailer(true);

								try{
								    //Server settings
								    $mail->isSMTP();                                           //Send using SMTP
								    $mail->Host       = 'smtp.hostinger.com';                       //Set the SMTP server to send through
								    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
								    $mail->Username   = 'bigaadocrequest@bigaadocrequest.online';            //SMTP username
								    $mail->Password   = 'BigaaDocRequest2021';                  //SMTP password
								    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable implicit TLS encryption
								    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

								    //Recipients
								    $mail->setFrom('bigaadocrequest@bigaadocrequest.online', 'Bigaa Certificate Request');
								    $mail->addAddress($_GET['email']);     //Add a recipient

								    //Content
								    $mail->isHTML(true);                                  //Set email format to HTML
								    $mail->Subject = 'Cancel Registration Confirmation';
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
									        	<h4 class="text-success text-center" style="margin: 30px 0 10px 0">Account Registration Cancelled</h4>
									        </div>
									        <div class="row justify-content-center">
									        	<div class="col-6">
									        		<p class="text-center mt-3" style="margin-bottom: 30px;"><b>Hello, you have successfully cancelled your registration in Bigaa Online Certificate Request. Thank you!</p>
									        	</div>
									        </div>
									  </body>
								    ';
								    $mail->send();
								} 
								catch (Exception $e){
									http_response_code(500);
								    die("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
								}
							}
							else if($result['isEmailVerified'] == 1){
								echo '
								<div class="container-fluid" id="wrapper">
									<div class="row justify-content-center">
										<div class="col-md-10 col-12">
											<div class="icon-box" style="margin-top: 75px;">
												<h5 class="text-success text-center m-0" style="font-size: 150px;"><span class="fas fa-question-circle"></span></h5>
											</div>
											<h4 class="text-success text-center">Oops, something went wrong.</h4>
											<h5 class="text-success text-center">Your email is already verified</h5>
										</div>
									</div>
								</div>
								';
							}
						}
						else{
							echo '
								<div class="container-fluid" id="wrapper">
									<div class="row justify-content-center">
										<div class="col-md-10 col-12">
											<div class="icon-box" style="margin-top: 75px;">
												<h5 class="text-success text-center m-0" style="font-size: 150px;"><span class="fas fa-question-circle"></span></h5>
											</div>
											<h4 class="text-success text-center">Oops, something went wrong.</h4>
											<h5 class="text-success text-center">Invalid link</h5>
										</div>
									</div>
								</div>
							';
						}
					}
				}
				$verify = new Functions();
				$verify->execute();
				unset($verify);
			}
			else{
				echo '
					<div class="container-fluid" id="wrapper">
						<div class="row justify-content-center">
							<div class="col-md-10 col-12">
								<div class="icon-box" style="margin-top: 75px;">
									<h5 class="text-success text-center m-0" style="font-size: 150px;"><span class="fas fa-question-circle"></span></h5>
								</div>
								<h4 class="text-success text-center">Oops, something went wrong.</h4>
								<h5 class="text-success text-center">Invalid link</h5>
							</div>
						</div>
					</div>
				';
			}
		}
		catch(PDOException|Exception $e){
			http_response_code(500);
			die($e->getMessage());
		}
	?>
	<script type='text/javascript' src='../js/jquery-3.6.0.min.js'></script>
	<script type='text/javascript' src='../js/popper.min.js'></script>
	<script type='text/javascript' src='../fontawesome/js/all.js'></script>
	<script>
		$(document).on('click', '.btn-success', function(){
			window.location.href = 'https://' + window.location.hostname;
		});
	</script>
</body>
</html>