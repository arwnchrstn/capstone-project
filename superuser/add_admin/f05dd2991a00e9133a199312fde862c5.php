<?php 
	require '../../includes/db_connection.php';

	Class Functions extends DbConn{
		//add new admin account
		public function addAdmin($user, $password, $position){
			try{
				$hashed_pwd = password_hash($password, PASSWORD_DEFAULT);
				$sql = "INSERT INTO admin_info (admin_id, admin_user, admin_password, position) VALUES(?,?,?,?)";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute(['ADMIN_'.time(), $user, $hashed_pwd, $position]))
					return 'ADMIN ADDED SUCCESSFULLY';
				else
				    throw new Exception('INSERT_SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
			    return $e->getMessage();
			}
		}
	}

	//add new admin account
	if(isset($_GET['username']) && isset($_GET['pass']) && isset($_GET['position'])){
		$addNewAdmin = new Functions();
		echo $addNewAdmin->addAdmin($_GET['username'], $_GET['pass'], strtoupper($_GET['position']));
		unset($addNewAdmin);
		exit();
	}
	else{
		echo 'NO ACTIONS PERFORMED'; 
		exit();
	}
?>