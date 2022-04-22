<?php 
	require 'db_connection.php';
	
	Class Functions extends DbConn{
		//admin login
		public function adminLogin($user, $pass){
			try{
				$fetched_pwd = "";
				$fetched_user = "";
				$fetched_id = "";
				$sql = "SELECT admin_id, admin_user, admin_password FROM admin_info WHERE admin_user = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$user])){
					while($rows = $stmt->fetch()){
						$fetched_id = $rows['admin_id'];
						$fetched_user = $rows['admin_user'];
						$fetched_pwd = $rows['admin_password'];
	    			}

					if($user == $fetched_user && password_verify($pass, $fetched_pwd)){
						session_regenerate_id();
						$_SESSION['ADMIN_ID'] = $fetched_id;
						return 'SUCCESS_ADMIN_LOGIN';
					}
					else{
						throw new Exception('ERROR_ADMIN_LOGIN');
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

	//login admin
	if(isset($_POST['admin-username']) && isset($_POST['admin-password'])){
		$adminLogin = new Functions();
		echo $adminLogin->adminLogin($_POST['admin-username'], $_POST['admin-password']);
		unset($adminLogin);
	}
?>