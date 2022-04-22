<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//convert date to string
		private function clearanceDate($yos){
			$currYear = date('Y');
			$secInMonth = 2628288;
			$calculate_month = floor((strtotime($currYear) - strtotime($yos)) / $secInMonth);
			
			if($calculate_month < 12){
				return "$calculate_month MONTHS";
			}
			else if($calculate_month > 12){
				$calculate_year = floor($calculate_month / 12);
				$calculate_month = $calculate_month % 12;
				return "$calculate_year YEAR(S) and $calculate_month MONTH(S)";
			}
		}

		//display resident info
		public function displayInfo($id){
			try{
				$data = array();
				$sql = "SELECT resident_info.first_name, IF(resident_info.middle_name='','',resident_info.middle_name) as middle_name, resident_info.last_name, IF(resident_info.suffix='N/A','',IF(resident_info.suffix='JR' OR resident_info.suffix='SR',CONCAT(resident_info.suffix,'.'),resident_info.suffix)) as suffix, resident_info.birthdate, resident_info.birthplace, resident_info.gender, resident_info.civil_status, resident_info.address, resident_info.mobile_number, resident_info.year_of_stay, resident_login_info.email, resident_info.picture_name, resident_info.voters_picture_name FROM resident_info INNER JOIN resident_login_info ON resident_info.resident_id = resident_login_info.resident_id WHERE resident_info.resident_id = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$id])){
					while($rows = $stmt->fetch()){
						$data += ['fname' => $rows['first_name']];
						$data += ['mname' => $rows['middle_name']];
						$data += ['lname' => $rows['last_name']];
						$data += ['suffix' => $rows['suffix']];
						$data += ['bdate' => date('F', strtotime($rows['birthdate'])).' '.date('m', strtotime($rows['birthdate'])).', '.date('Y', strtotime($rows['birthdate']))];
						$data += ['bplace' => $rows['birthplace']];
						$data += ['gender' => $rows['gender']];
						$data += ['civilstat' => $rows['civil_status']];
						$data += ['address' => $rows['address']];
						$data += ['year_of_stay' => $this->clearanceDate($rows['year_of_stay'])];
						$data += ['mobileno' => $rows['mobile_number']];
						$data += ['email' => $rows['email']];
						$data += ['picture' => $rows['picture_name']];
						$data += ['voters' => $rows['voters_picture_name']];
					}

					return $data;
				}
				else
					return 'SQL_ERROR';
			}
			catch(PDOException|Exception|Error $e){
				return 'Oops, an error occurred.';
			}
		}
	}

	//display info
	if(isset($_POST['id'])){
		$display = new Functions();
		echo json_encode($display->displayInfo($_POST['id']));
		unset($display);
	}
?>