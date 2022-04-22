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
		//send email after account creation
		private function sendEmailConfirmation($email, $verificationKey, $fullname){
			//Create an instance; passing `true` enables exceptions
			$mail = new PHPMailer(true);

			try{
			    //Server settings
			    $mail->isSMTP();                                            //Send using SMTP
			    $mail->Host       = 'smtp.hostinger.com';                       //Set the SMTP server to send through
			    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			    $mail->Username   = 'bigaadocrequest@bigaadocrequest.online';   //SMTP username
			    $mail->Password   = 'BigaaDocRequest2021';                  //SMTP password
			    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable implicit TLS encryption
			    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			    //Recipients
			    $mail->setFrom('bigaadocrequest@bigaadocrequest.online', 'Bigaa Certificate Request');
			    $mail->addAddress($email);     //Add a recipient

			    //Content
			    $mail->isHTML(true);                                  //Set email format to HTML
			    $mail->Subject = 'Account Confirmation';
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
				        	<h4 class="text-success text-center" style="margin: 30px 0 10px 0">Account Confirmation</h4>
				        </div>
				        <div class="row justify-content-center">
				        	<div class="col-6">
				        		<p class="text-center mt-3" style="margin-bottom: 30px;">Hello '.$fullname.', you have successfully created an account for Online Certificate Request System of Brgy. Bigaa</p>
				        	</div>
				        </div>
				        <div class="row" style="display: flex; justify-content: center!important;">
				        	<a href="'."https://".$_SERVER['SERVER_NAME']."/authenticate/verify.php?key=".$verificationKey.'" target="_blank" style="display: block; margin: 0 auto; font-size: 18px; padding: 8px 15px; border-radius: 5px; border: none; background: #28a745!important; color: white; font-weight: 500; text-decoration: none!important;">Confirm Email</a>
				        </div>
				        <div class="row justify-content-center mt-4">
				        	<div class="col-6">
				        		<p class="text-center mt-3" style="margin-top: 30px;">Click the button above to confirm your account. If you are having trouble clicking the button, click the link below instead: </p>
				        	</div>
				        </div>
				        <div class="row" style="display: flex; justify-content: center!important;">
				        	<a href="'."https://".$_SERVER['SERVER_NAME']."/authenticate/verify.php?key=".$verificationKey.'" target="_blank" style="display: block; margin: 0 auto; font-size: 15px; margin-top: 20px; text-decoration: underline;">'."https://".$_SERVER['SERVER_NAME']."/authenticate/verify.php?key=".$verificationKey.'</a>
				        </div>
				        <div class="row justify-content-center mt-4">
				        	<div class="col-6">
				        		<p class="text-center mt-3" style="margin-top: 30px;"><b>If this isn\'t you, please <a href="'."https://".$_SERVER['SERVER_NAME']."/authenticate/cancel_registration.php?key=".$verificationKey.'&email='.$email.'" target="_blank" style="text-decoration: underline;">click here</a> to cancel the registration. Thank you!</p>
				        	</div>
				        </div>
				      </div>
				  </body>
			    ';
			    if($mail->send())
					return 'SUCCESS_CREATE';
				else
					throw new Exception('MAILER_ERROR');
			} 
			catch (Exception $e){
			    return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}, {$e->getMessage()}";
			}
		}

		//check email if it exist when creating an account
		public function isEmailExist($email){
			try{
				$sql = "SELECT email FROM resident_login_info WHERE email = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$email])){
					$results = $stmt->fetchAll();

					if(count($results) > 0){
						return true;
					}
					else{
						return false;
					}
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}

		//create user account
		public function addResident($email, $pass, $fname, $mname, $lname, $suffix, $bdate, $bplace, $gender, $civilstat, $address, $year_of_stay, $mobileno, $picture, $voters_pic){
			try{
				date_default_timezone_set('Asia/Manila');
			    $date_today = date('Y-m-d H:i:s');

				//decode picture
				$hashed_pwd = password_hash($pass, PASSWORD_DEFAULT);
				$data = $picture;
				if(strpos($data, 'base64') === false){
					$base64_decode = file_get_contents('../'.$data);
				}
				else{
					$image_array1 = explode(';', $data);
					$image_array2 = explode(',', $image_array1[1]);
					$base64_decode = base64_decode($image_array2[1]);
				}
				$img_name = time().".png";
				$img_path = "../uploaded image/profile pictures/".$img_name;

				//decode voters id picture
				$data_voters = $voters_pic;
				$image_array1_vot = explode(';', $data_voters);
				$image_array2_vot = explode(',', $image_array1_vot[1]);
				$base64_decode_vot = base64_decode($image_array2_vot[1]);
				$img_name_voters = time().'votersID'.".png";
				$img_path_voters = "../uploaded image/voters id pictures/".$img_name_voters;

				//insert new account information to DB
				$res_id = 'BIGAA-'.time();
				$sql = "INSERT INTO resident_info (resident_id, first_name, middle_name, last_name, suffix, birthdate, birthplace, gender, civil_status, address, year_of_stay, mobile_number, picture_name, voters_picture_name, account_created) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$stmt = $this->connect()->prepare($sql);
				if(!$stmt->execute([$res_id, $fname, $mname, $lname, $suffix, $bdate, $bplace, $gender, $civilstat, $address, $year_of_stay, $mobileno, $img_name, $img_name_voters, $date_today]))
					throw new Exception('INSERT_SQL_ERROR');

				//insert to verification table inside DB
				$vkey = md5(time());
				$stmt2 = $this->connect()->prepare("INSERT INTO verification (resident_id, verification_key) VALUES (?,?)");
				if(!$stmt2->execute([$res_id, $vkey]))
					throw new Exception('INSERT_SQL_ERROR');

				//insert to user login information
				$stmt3 = $this->connect()->prepare("INSERT INTO resident_login_info (resident_id, email, password) VALUES (?,?,?)");
				if(!$stmt3->execute([$res_id, $email, $hashed_pwd]))
					throw new Exception('INSERT_SQL_ERROR');
				
				//create the image file for the profile picture and voters ID
				file_put_contents($img_path, $base64_decode);
				file_put_contents($img_path_voters, $base64_decode_vot);

				return $this->sendEmailConfirmation($email, $vkey, ucwords(strtolower($fname).' '.strtolower($lname)));
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//check email if exist when creating acct
	if(isset($_POST['checkEmail'])){
		$checkEmail = new Functions();
		echo $checkEmail->isEmailExist($_POST['checkEmail']);
		unset($checkEmail);
	}

	//add resident
	if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['fname']) && isset($_POST['mname']) && isset($_POST['lname']) && isset($_POST['suffix']) && isset($_POST['bdate']) && isset($_POST['bplace']) && isset($_POST['gender']) && isset($_POST['civilstat']) && isset($_POST['year_of_stay']) && isset($_POST['address']) && isset($_POST['mobileno']) && isset($_POST['profile_pic']) && isset($_POST['voters_pic'])){
		$addNewResident = new Functions();
		echo $addNewResident->addResident($_POST['email'], $_POST['password'], $_POST['fname'], $_POST['mname'], $_POST['lname'], $_POST['suffix'], $_POST['bdate'], $_POST['bplace'], $_POST['gender'], $_POST['civilstat'], $_POST['address'], $_POST['year_of_stay'], $_POST['mobileno'], $_POST['profile_pic'], $_POST['voters_pic']);
		unset($addNewResident);
	}
?>