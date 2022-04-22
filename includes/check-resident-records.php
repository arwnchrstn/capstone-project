<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		public function getResidentRecordCount(){
			try{
				$data = array();

				$stmt = $this->connect()->prepare("SELECT COUNT(*) FROM resident_info INNER JOIN verification ON resident_info.resident_id = verification.resident_id");
				if($stmt->execute()){
					$result = $stmt->fetch();

					if($result)
						$data += ['record_count' => $result['COUNT(*)']];

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

	$showRecord = new Functions();
	echo json_encode($showRecord->getResidentRecordCount());
	unset($showRecord);
?>