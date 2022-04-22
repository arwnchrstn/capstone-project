<?php
	require 'db_connection.php';

	Class Functions extends DbConn{
		//convert date to string
		private function convertDate($ts){
			$array = explode(' ', $ts);
			return date("F", strtotime($array[0]))." ".date("d", strtotime($array[0])).", ".date("Y", strtotime($array[0]))." ".date('h:i A', strtotime($array[1]));
		}

		//load request history on user
		public function loadTableUserRequest(){
			try{
				$tableData = "";
				$type = "";
				$action = "";
				$remarks = "";
				$statusBadge = "";
				$date_completed = "";

				$sql = "SELECT request_id, request_type, purpose, request_status, request_date, date_completed, remarks FROM requests_list WHERE resident_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$_SESSION['ID']])){
					foreach($stmt->fetchAll() as $rows) {
						switch ($rows['request_type']) {
							case 'INDIGENCY':
								$type = 'Certificate of Indigency';
								break;
							case 'RESIDENCY':
								$type = 'Certificate of Residency';
								break;
							case 'CLEARANCE':
								$type = 'Barangay Clearance';
								break;
							case 'CEDULA':
								$type = 'Cedula';
								break;
							case 'BRGY_ID':
								$type = 'Barangay ID';
								break;
							case 'BUSINESS_PERMIT':
								$type = 'Business Permit';
								break;
							case 'GOOD_HEALTH':
								$type = 'Certificate of Good Health';
								break;
						}

						switch ($rows['request_status']) {
							case 'PENDING':
								$action = '<button class="btn btn-danger btn-sm delete-request" id="'.$rows['request_id'].'">Cancel Request</button>';
								break;
							case 'PROCESSING':
								$action = '<button class="btn btn-danger btn-sm delete-request" id="'.$rows['request_id'].'">Cancel Request</button>';
								break;
							default:
								$action = 'No action';
								break;
						}

						switch ($rows['request_status']) {
							case 'PENDING':
								$statusBadge = '<span class="badge badge-warning" style="font-size: 13px;">PENDING</span>';
								break;
							case 'PROCESSING':
								$statusBadge = '<span class="badge badge-warning" style="font-size: 13px;">PROCESSING</span>';
								break;
							case 'FOR PICKUP':
								$statusBadge = '<span class="badge badge-info" style="font-size: 13px;">FOR PICKUP</span>';
								break;
							case 'COMPLETED':
								$statusBadge = '<span class="badge badge-success" style="font-size: 13px;">COMPLETED</span>';
								break;
							case 'CANCELLED':
								$statusBadge = '<span class="badge badge-danger" style="font-size: 13px;">CANCELLED</span>';
								break;
							case 'DECLINED':
								$statusBadge = '<span class="badge badge-danger" style="font-size: 13px;">DECLINED</span>';
								break;
						}

						if($rows['date_completed'] == ""){
							$date_completed = '--';
						}
						else{
							$date_completed = $this->convertDate($rows['date_completed']);
						}

						if($rows['remarks'] == ""){
							$remarks = 'None';
						}
						else{
							$remarks = $rows['remarks'];
						}

						$tableData .= 
							'<tr id="'.$rows['request_id'].'">
								<td class="text-center" style="vertical-align: middle;">'.$type.'</td>
								<td class="text-center" style="vertical-align: middle;">'.$statusBadge.'</td>
								<td class="text-center" style="vertical-align: middle;">'.$rows['purpose'].'</td>
								<td class="text-center" style="vertical-align: middle;">'.$this->convertDate($rows['request_date']).'</td>
								<td class="text-center" style="vertical-align: middle;">'.$date_completed.'</td>
								<td class="text-center" style="vertical-align: middle;">'.$remarks.'</td>
								<td class="text-center" style="vertical-align: middle;">'.$action.'</td>
							</tr>';
					}

					$tableFormat = '
						<thead style="background-color: rgb(69, 176, 111);">
							<tr>
								<th class="text-white text-center" style="width: 160px; vertical-align: middle;">Type</th>
								<th class="text-white text-center" style="width: 120px; vertical-align: middle;">Status</th>
								<th class="text-white text-center" style="width: 200px; vertical-align: middle;">Purpose</th>
								<th class="text-white text-center" style="width: 180px; vertical-align: middle;">Date Requested</th>
								<th class="text-white text-center" style="width: 180px; vertical-align: middle;">Date Completed</th>
								<th class="text-white text-center" style="width: 250px; vertical-align: middle;">Remarks</th>
								<th class="text-white text-center" style="width: 200px; vertical-align: middle;">Action</th>
							</tr>
						</thead>
						<tbody id="req-table-data">
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

	//load request history of the user
	$loadData = new Functions();
	echo $loadData->loadTableUserRequest();
	unset($loadData);
?>