<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		public function getTransactionCount($start, $end){
			try{
				$data = array();

				$stmt = $this->connect()->prepare("SELECT COUNT(*) FROM requests_list WHERE request_status = 'COMPLETED' AND (date_completed >= ? AND date_completed <= ?)");
				if($stmt->execute([$start, $end.' 23:59:59'])){
					$result = $stmt->fetch();

					if($result)
						$data += ['transaction_count' => $result['COUNT(*)']];

					return $data;
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	if(isset($_POST['start']) && isset($_POST['end'])){
		$showTransactCount = new Functions();
		echo json_encode($showTransactCount->getTransactionCount($_POST['start'], $_POST['end']));
		unset($showTransactCount);
	}
?>