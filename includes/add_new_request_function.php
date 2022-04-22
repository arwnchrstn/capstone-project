<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//add new request
		public function addNewRequest($req_type,$purpose,$timeMod){
			try{
				date_default_timezone_set('Asia/Manila');
			    $date_today = date('Y-m-d H:i:s');

				$sql = "INSERT INTO requests_list (request_id, resident_id, request_type, purpose, request_status, request_date, remarks) VALUES (?,?,?,?,?,?,?)";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute(['RQST_'.time()+$timeMod, $_SESSION['ID'], $req_type, $purpose, 'PENDING', $date_today, 'Your request is filed and waiting to be processed']))
					return 'REQUEST_SUCCESS';
				else
					throw new Exception('INSERT_SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//add new request
	if(isset($_POST['purpose']) && isset($_POST['request'])){
		$addRequest = new Functions();
		$requestCount = count($_POST['request']);

		for($i=0; $i<$requestCount; $i++){
			if($_POST['request'][$i] != '' && $_POST['purpose'][$i] != '')
				echo $addRequest->addNewRequest($_POST['request'][$i], strtoupper($_POST['purpose'][$i]), $i);
		}
		unset($addRequest);
	}
?>