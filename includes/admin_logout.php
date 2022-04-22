<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//logout, destroy sessions
		public function logout(){
			try{
				//destroy the session
				session_unset();
				session_destroy();
				return 'LOGOUT';
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}
	//logout
	$logout = new Functions();
	echo $logout->logout();
	unset($logout);
?>