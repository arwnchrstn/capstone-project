<?php
	//start sessions
	session_set_cookie_params(0,'/','',true,true);
	session_start();

	//database connect
	Class DbConn{
		private $dbHost = 'localhost';
		private $dbUser = 'u546471981_bigaa_online';
		private $dbPass = '+BigaaDocRequest2021+';
		private $dbName = 'u546471981_bigaaDb';

		protected function connect(){
			try{
				$pdo = new PDO('mysql:host='.$this->dbHost.';dbname='.$this->dbName,$this->dbUser,$this->dbPass);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				return $pdo;
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}
