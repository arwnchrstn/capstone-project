<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//count pending request (Admin)
		public function countPendingRequest(){
			try{
				$sql = "SELECT COUNT(*) FROM requests_list WHERE request_status = 'PENDING'";
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

	//Display count of corresponding request in admin dash
	//get count of pending request (Admin)
	$getCount = new Functions();
	echo $getCount->countPendingRequest();
	unset($getCount);
?>