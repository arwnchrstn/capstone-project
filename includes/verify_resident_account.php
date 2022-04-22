<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//verify account
		public function verifyAccount($id){
			try{
				$sql = "UPDATE verification SET isAccountVerified = 1 WHERE resident_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$id])){
					return 'SUCCESS_VERIFY';
				}
				else{
					throw new Exception('UPDATE_SQL_ERROR');
				}
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//verify user accounts
	if(isset($_POST['id'])){
		$verify = new Functions();
		echo $verify->verifyAccount($_POST['id']);
		unset($verify);
	}
?>