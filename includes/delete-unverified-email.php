<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		public function deleteAccount($id){
			try{
				$sql = "DELETE FROM resident_info WHERE resident_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$id]))
					return 'SUCCESS_DELETE';
				else
					throw new Exception('DELETE_SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	if(isset($_POST['id_delete'])){
		$delete = new Functions();
		echo $delete->deleteAccount($_POST['id_delete']);
		unset($delete);
	}
?>