<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//cancel request from the user
		public function cancelRequest($request_id){
			try{
				$sql = "UPDATE requests_list SET request_status = ?, remarks = ? WHERE request_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute(['CANCELLED', 'Cancelled by the user', $request_id]))
					return 'SUCCESS_CANCEL_REQ';
				else
					throw new Exception('UPDATE_SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//cancel request
	if(isset($_POST['requestNo'])){
		$deleteReq = new Functions();
		echo $deleteReq->cancelRequest($_POST['requestNo']);
		unset($deleteReq);
	}
?>