<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//resident login
		public function loginResident($email, $password){
			try{
				$fetched_pwd = "";
				$fetched_email = "";
				$fetched_id = "";
				$isEmailVerified = "";
				$sql = "SELECT resident_login_info.resident_id, resident_login_info.email, resident_login_info.password, verification.isEmailVerified FROM resident_login_info INNER JOIN verification ON resident_login_info.resident_id = verification.resident_id WHERE email = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$email])){
					while($rows = $stmt->fetch()){
						$fetched_pwd = $rows['password'];
						$fetched_email = $rows['email'];
						$fetched_id = $rows['resident_id'];
						$isEmailVerified = $rows['isEmailVerified'];
					}

					if($email == $fetched_email && password_verify($password, $fetched_pwd) && $isEmailVerified == 1){
						session_regenerate_id(true);
						$_SESSION['ID'] = $fetched_id;

						return 'SUCCESS_LOGIN';
					}
					else{
						throw new Exception('NO_RECORD');
					}
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}
	
	//resident login
	if(isset($_POST['email_user']) && isset($_POST['password_user'])){
		$login = new Functions();
		echo $login->loginResident($_POST['email_user'], $_POST['password_user']);
		unset($login);
	}
?>