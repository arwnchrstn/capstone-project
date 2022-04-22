<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		public function getControlNumber(){
			try{
				$sql = "SELECT control_no FROM control_number WHERE control_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute(['CONTROL_NUMBER']))
					return $stmt->fetch()['control_no'];
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	$controlNumber =  new Functions();
	echo $controlNumber->getControlNumber();
	unset($controlNumber);
?>