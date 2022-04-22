<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//convert date to string
		private function convertDate($ts){
			$array = explode(' ', $ts);
			return date("F", strtotime($array[0]))." ".date("d", strtotime($array[0])).", ".date("Y", strtotime($array[0]))." ".date('h:i A', strtotime($array[1]));
		}

		//load table for non verified users
		public function loadTable($email_status){
			try{
				$tableFormat = '';
				$tableData = '';
				$action = '';
				$dateToday = date('Y-m-d h:i:s');

				$sql = "SELECT CONCAT(resident_info.first_name, ' ', IF(resident_info.middle_name='N/A','',resident_info.middle_name), ' ', resident_info.last_name, ' ', IF(resident_info.suffix='N/A', '', resident_info.suffix)) AS name, resident_info.resident_id, resident_info.account_created, verification.isEmailVerified FROM resident_info INNER JOIN verification ON resident_info.resident_id = verification.resident_id WHERE verification.isAccountVerified = 0 ORDER BY resident_info.account_created DESC";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute()){
					$results = $stmt->fetchAll();

					foreach($results as $rows){
						switch($rows['isEmailVerified']){
							case $email_status:
								if($email_status == 1)
									$action = '<button class="btn btn-success btn-sm verify" id="'.$rows['resident_id'].'">Verify Account</button>';
								else{
									$dateInterval = (strtotime($dateToday) - strtotime($rows['account_created'])) / 86400;
									if($dateInterval >= 10)
										$action = '<button class="btn btn-danger btn-sm delete-account" id="'.$rows['resident_id'].'"> <span class="fas fa-trash"></span> Delete</button>';
									else
										$action = 'No action';
								}

								$tableData .= '
									<tr id="'.$rows['resident_id'].'">
										<td class="text-center" style="vertical-align: middle;">'.$rows['name'].'</td>
										<td class="text-center" style="vertical-align: middle;">'.$this->convertDate($rows['account_created']).'</td>
										<td class="text-center" style="vertical-align: middle;"><button class="btn btn-info btn-sm view-info" id="'.$rows['resident_id'].'">View Information</button></td>
										<td class="text-center" style="vertical-align: middle;">'.$action.'</td>
									</tr>
								';
								break;
						}
					}

					$tableFormat = '
						<thead style="background-color: rgb(69, 176, 111);">
							<tr>
								<th class="text-white text-center" style="width: 180px; vertical-align: middle;">Account Name</th>
								<th class="text-white text-center" style="width: 150px; vertical-align: middle;">Date Created</th>
								<th class="text-white text-center" style="width: 150px; vertical-align: middle;">Account Information</th>
								<th class="text-white text-center" style="width: 150px; vertical-align: middle;">Action</th>
							</tr>
						</thead>
						<tbody id="table-data">
							'.$tableData.'
						</tbody>
					';

					return $tableFormat;
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	if(isset($_POST['status'])){
		$loadTable = new Functions();
		echo $loadTable->loadTable($_POST['status']);
		unset($loadTable);
	}
?>