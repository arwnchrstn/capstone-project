<?php
	require 'db_connection.php';

	Class Functions extends DbConn{
		//update for pickup request to completed
		public function updatePickupStatus($req_id){
			try{
				date_default_timezone_set('Asia/Manila');
			    $date_today = date('Y-m-d H:i:s');

				$sql = "UPDATE requests_list SET request_status = ?, remarks = ?, date_completed = ? WHERE request_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute(['COMPLETED', 'Request completed', $date_today, $req_id]))
					return 'SUCCESS_COMPLETE_REQ';
				else
					throw new Exception('UPDATE_SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//update request status for for pickup request (Admin)
	if(isset($_POST['complete_req_no'])){
		$updateComplete = new Functions();
		echo $updateComplete->updatePickupStatus($_POST['complete_req_no']);
		unset($updateComplete);
	}
?>