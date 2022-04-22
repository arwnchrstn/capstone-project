<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		public function verifyOTP($otp, $id){
			try{
				if(isset($_COOKIE['otp'])){
					$cookie_otp = $_COOKIE['otp'];

					if(password_verify($otp, $cookie_otp)){
						$sql = "UPDATE verification SET isMobileVerified = ? WHERE resident_id = ?";
						$stmt = $this->connect()->prepare($sql);
						if($stmt->execute([1,$id])){
							setcookie('otp', '', time()-3600, '/', '', true, true);
							return 'OTP_VERIFIED';
						}
						else
							throw new Exception('UPDATE_SQL_ERROR');
					}
					else
						throw new Exception('OTP_FAILED');
				}
				else
					throw new Exception('OTP_FAILED');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	if(isset($_POST['otp'])){
		$verify = new Functions();
		echo $verify->verifyOTP($_POST['otp'], $_SESSION['ID']);
		unset($verify);
	}
?>