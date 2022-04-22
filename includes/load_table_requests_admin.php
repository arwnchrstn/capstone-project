<?php
	require 'db_connection.php';

	Class Functions extends DbConn{
		//convert date to string
		private function convertDate($ts){
			$array = explode(' ', $ts);
			return date("F", strtotime($array[0]))." ".date("d", strtotime($array[0])).", ".date("Y", strtotime($array[0]))." ".date('h:i A', strtotime($array[1]));
		}

		//load table on admin dashboard (Admin)
		public function loadRequest($status){
			try{
				$tableData = "";
				$type = "";
				$action = ""; 
				$acctStatus = '';
				$nameFormat = '';
				$copies = '';
				$controlNumber = '';

				$sql = "SELECT request_id, CONCAT(resident_info.first_name, ' ', IF(resident_info.middle_name='','',resident_info.middle_name) ,' ',resident_info.last_name, ' ', IF(resident_info.suffix='N/A', '', resident_info.suffix)) AS name_of_requestor, resident_info.resident_id, requests_list.request_type, requests_list.purpose, requests_list.request_date, requests_list.request_status, requests_list.request_id, requests_list.date_completed, requests_list.ctrl_no_clearance, requests_list.admin_processed, verification.isAccountVerified FROM resident_info INNER JOIN requests_list ON resident_info.resident_id = requests_list.resident_id INNER JOIN verification ON requests_list.resident_id = verification.resident_id WHERE request_status = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$status])){
					foreach ($stmt->fetchAll() as $rows){
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
							/*case 'CEDULA':
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
								break;*/
						}

						switch($rows['request_status']){
							case 'PENDING':
								$action = '<button class="btn btn-success btn-sm process-request ml-1 mb-1" id="'.$rows['request_id'].'">Process</button><button class="btn btn-danger btn-sm decline-request ml-1 mb-1" id="'.$rows['request_id'].'">Decline</button>';
								$nameFormat = $rows['name_of_requestor'].'<button class="btn btn-info btn-sm view-info-btn" id="'.$rows['resident_id'].'" style="display: block; width: 100%;">View Information</button>';
								break;
							case 'PROCESSING':
								$nameFormat = $rows['name_of_requestor'];
								$action = '<button class="btn btn-success btn-sm generate-pdf ml-1 mb-1" id="'.$rows['request_id'].'">Generate Certificate</button>';
								break;
							case 'FOR PICKUP':
								$action = '<button class="btn btn-success btn-sm complete-request ml-1 mb-1" id="'.$rows['request_id'].'">Mark as Completed</button>';
								$nameFormat = $rows['name_of_requestor'];
								break;
							case 'COMPLETED':
								$nameFormat = $rows['name_of_requestor'];
								break;
						}

						switch($rows['isAccountVerified']){
							case 0:
								$acctStatus = '<span class="badge badge-warning" style="font-size: 13px;">NOT VERIFIED</span>';
								break;
							case 1:
								$acctStatus = '<span class="badge badge-success" style="font-size: 13px;">VERIFIED</span>';
								break;
						}

						if($rows['date_completed'] == ""){
							$date_completed = '--';
						}
						else{
							$date_completed = $this->convertDate($rows['date_completed']);
						}

						if($rows['ctrl_no_clearance'] != 0)
							$controlNumber = $rows['ctrl_no_clearance'];
						else
							$controlNumber = '----';

						$tableData .= 
						'<tr id="'.$rows['request_id'].'">
							<td class="text-center" style="vertical-align: middle;">'.$rows['request_id'].'</td>
							<td class="text-center" style="vertical-align: middle;">'.$nameFormat.'</td>
							<td class="text-center" style="vertical-align: middle;">'.$acctStatus.'</td>
							<td class="text-center" style="vertical-align: middle;">'.$type.'</td>
							<td class="text-center" style="vertical-align: middle;">'.$rows['purpose'].'</td>
							<td class="text-center" style="vertical-align: middle;">'.$this->convertDate($rows['request_date']).'</td>
							<td class="text-center" style="vertical-align: middle;">'.$date_completed.'</td>
							<td class="text-center" style="vertical-align: middle;">'.$controlNumber.'</td>
							<td class="text-center" style="vertical-align: middle;">'.$action.'</td>
							<td class="text-center" style="vertical-align: middle;">'.$rows['admin_processed'].'</td>
						</tr>';
					}

					$tableFormat = '
						<thead style="background-color: rgb(69, 176, 111);">
							<tr>
								<th class="text-white text-center" style="width: 170px; vertical-align: middle;">Request Number</th>
								<th class="text-white text-center" style="width: 180px; vertical-align: middle;">Name of Requestor</th>
								<th class="text-white text-center" style="width: 150px; vertical-align: middle;">Account Status</th>
								<th class="text-white text-center" style="width: 100px; vertical-align: middle;">Type</th>
								<th class="text-white text-center" style="width: 200px; vertical-align: middle;">Purpose</th>
								<th class="text-white text-center" style="width: 150px; vertical-align: middle;">Date Requested</th>
								<th class="text-white text-center" style="width: 140px; vertical-align: middle;">Date Completed</th>
								<th class="text-white text-center" style="width: 130px; vertical-align: middle;">Clearance control no.</th>
								<th class="text-white text-center" style="width: 210px; vertical-align: middle;">Action</th>
								<th class="text-white text-center" style="width: 210px; vertical-align: middle;">Processed By</th>
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
?>