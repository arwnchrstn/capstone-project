<?php
	session_set_cookie_params(0,'/','',true,true);
	session_start();

	Class Functions{
		//logout, destroy sessions
		public function logout(){
			try{
				session_unset();
				session_destroy();
				return 'LOGOUT';
			}
			catch(Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//logout
	$logout = new Functions();
	echo $logout->logout();
	unset($logout);
?>