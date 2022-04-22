<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//convert date to string
		private function convertDate($ts){
			$array = explode(' ', $ts);
			return date("F", strtotime($array[0]))." ".date("d", strtotime($array[0])).", ".date("Y", strtotime($array[0]))." ".date('h:i A', strtotime($array[1]));
		}

		public function loadVerifiedUsers(){
			try{
				$tableFormat = '';
				$tableData = '';

				$sql = "SELECT CONCAT(resident_info.first_name, ' ', IF(resident_info.middle_name='N/A','',resident_info.middle_name), ' ', resident_info.last_name, ' ', IF(resident_info.suffix='N/A', '', resident_info.suffix)) AS name, resident_info.resident_id, resident_info.account_created FROM resident_info INNER JOIN verification ON resident_info.resident_id = verification.resident_id WHERE verification.isAccountVerified = 1 ORDER BY name DESC";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute()){
					foreach($stmt->fetchAll() as $rows){
						$tableData .= '
								<tr>
									<td class="text-center" style="vertical-align: middle;">'.$rows['name'].'</td>
									<td class="text-center" style="vertical-align: middle;">'.$this->convertDate($rows['account_created']).'</td>
									<td class="text-center" style="vertical-align: middle;">
										<button class="btn btn-success view-info tooltip-container" id="'.$rows['resident_id'].'"><span class="fas fa-eye"></span><span class="tooltiptext">View Information</span></button>
										<button class="btn btn-success generate-cert tooltip-container ml-1" id="'.$rows['resident_id'].'"><span class="fas fa-file-alt"></span><span class="tooltiptext">Generate Certificate</span></button>
									</td>
								</tr>
							';
					}

					$tableFormat = '
						<thead style="background-color: rgb(69, 176, 111);">
							<tr>
								<th class="text-white text-center" style="vertical-align: middle; width: 200px;">Account Name</th>
								<th class="text-white text-center" style="vertical-align: middle; width: 200px;">Date Created</th>
								<th class="text-white text-center" style="vertical-align: middle; width: 200px;">Actions</th>
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

	$loadTable = new Functions();
	echo $loadTable->loadVerifiedUsers();
	unset($loadTable);
?>