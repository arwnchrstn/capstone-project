<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		public function updateCtrlNo($ctrl){
			try{
				$sql = "UPDATE control_number SET control_no = ? WHERE control_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$ctrl, 'CONTROL_NUMBER']))
					return 'SUCCESS_UPDATE';
				else
					throw new Exception('UPDATE_SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	if(isset($_POST['ctrl'])){
		$updateCtrl = new Functions();
		echo $updateCtrl->updateCtrlNo($_POST['ctrl']);
		unset($updateCtrl);
	}
?>