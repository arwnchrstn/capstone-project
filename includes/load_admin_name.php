<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		public function displayUser($admin_id){
			//fetch the username of the current session
			try{
				$sql = 'SELECT admin_user FROM admin_info WHERE admin_id = ? LIMIT 1';
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$admin_id]))
					return $stmt->fetch()['admin_user'];
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}
?>