<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		public function getVerifiedCount(){
			try{
				$sql = "SELECT COUNT(*) FROM verification WHERE isEmailVerified = 1 AND isAccountVerified = 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute()){
					$result = $stmt->fetch();
					return ($result) ? $result['COUNT(*)'] : 'FAILED_TO_LOAD';
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//display number of non verified users
	$getCount = new Functions();
	echo $getCount->getVerifiedCount();
	unset($getCount);
?>