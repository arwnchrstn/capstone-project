<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//fetch data from database to display in dashboard
		public function getResidentData($id){
			try{
				$sql = "SELECT resident_info.resident_id, resident_info.first_name, resident_info.middle_name, resident_info.last_name, resident_info.suffix, resident_info.birthdate, resident_info.birthplace, resident_info.gender, resident_info.civil_status, resident_info.address, resident_info.mobile_number,resident_login_info.email, resident_info.year_of_stay, resident_info.picture_name, resident_info.voters_picture_name FROM resident_info INNER JOIN resident_login_info ON resident_info.resident_id = resident_login_info.resident_id WHERE resident_info.resident_id = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$id])){
					$results = $stmt->fetch();
					return $results;
				}
				else
					return 'SQL_ERROR';
			}
			catch(PDOException|Exception|Error $e){
				return 'Oops, an error occurred.';
			}
		}

		//check account status
		public function accountStatus($id){
			try{
				$sql = "SELECT isAccountVerified FROM verification WHERE resident_id = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$id])){
					$result = $stmt->fetch();

					if($result)
						return $result['isAccountVerified'];
					else
						throw new Exception('ERROR_FETCH');
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}

		//check mobile if verified
		public function isMobileVerified($id){
			try{
				$sql = "SELECT isMobileVerified FROM verification WHERE resident_id = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$id])){
					$result = $stmt->fetch();

					if($result)
						return $result['isMobileVerified'];
					else
						return 'ERROR_FETCH';
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}
?>