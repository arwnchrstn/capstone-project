<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		//convert date to string
		private function convertDate($ts){
			$array = explode(' ', $ts);
			return date("F", strtotime($array[0]))." ".date("d", strtotime($array[0])).", ".date("Y", strtotime($array[0]))." ".date('h:i A', strtotime($array[1]));
		}

		public function generateReportPdf($start, $end){
			try{
				date_default_timezone_set('Asia/Manila');
				$date = date('Y-m-d h:i A');
				$tableData = '';
				$type = '';

				$sql = "SELECT CONCAT(resident_info.last_name, ', ', resident_info.first_name, ' ', IF(resident_info.middle_name = '','',resident_info.middle_name),' ', IF(resident_info.suffix = 'SR' OR resident_info.suffix = 'JR', CONCAT(resident_info.suffix, '.'), IF(resident_info.suffix='N/A','', resident_info.suffix))) as name, resident_info.resident_id, requests_list.request_type, requests_list.date_completed FROM resident_info INNER JOIN requests_list ON resident_info.resident_id = requests_list.resident_id WHERE requests_list.date_completed >= ? AND requests_list.date_completed <= ? ORDER BY requests_list.date_completed ASC";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$start, $end.' 23:59:59'])){
					$results = $stmt->fetchAll();

					foreach($results as $rows){
						switch($rows['request_type']){
							case 'INDIGENCY':
								$type = 'Certificate of Indigency';
								break;
							case 'CLEARANCE':
								$type = 'Barangay Clearance';
								break;
							case 'RESIDENCY':
								$type = 'Certificate of Residency';
								break;
						}

						$tableData .= '
							<tr>
								<th style="width: 100px; font-weight: 400; font-size: 12px;">'.$rows['resident_id'].'</th>
								<th style="width: 150px; font-weight: 400; font-size: 12px;">'.$rows['name'].'</th>
								<th style="width: 200px; font-weight: 400; font-size: 12px;">'.$type.'</th>
								<th style="width: 150px; font-weight: 400; font-size: 12px;">'.$this->convertDate($rows['date_completed']).'</th>
							</tr>
						';
					}

					$html = '
						<style>
							*{
								font-family: Arial, Sans-Serif;
							}
							table{
								width: 100%;
								border-collapse: collapse;
								table-layout: fixed;
							}
							td, th{
								border: 1px solid black;
								text-align: left;
								padding: 5px;
								word-wrap: break-word;
							}
							.row{
								display: flex;
								flex-direction: row;
								justify-content: center;
								margin-bottom: 50px;
							}
						</style>

						<h5 style="text-align: center; margin: 0;">BARANGAY BIGAA ONLINE CERTIFICATE REQUEST SYSTEM REPORT</h5>
						<h6 style="text-align: center; margin: 10px 0 20px 0;">Barangay Bigaa, City of Cabuyao, Laguna</h6>
						<h6 style="text-align: center; margin: 0 0 20px 0;"><b>List of completed transactions from '.date("F", strtotime($start)).' '.date("d", strtotime($start)).', '.date("Y", strtotime($start)).' to '.date("F", strtotime($end)).' '.date("d", strtotime($end)).', '.date("Y", strtotime($end)).'</h6>

						<table border="1" colspacing="0">
							<thead">
								<tr>
									<th style="width: 150px; font-size: 13px;"><b>Resident ID</th>
									<th style="width: 150px; font-size: 13px;"><b>Name</th>
									<th style="width: 150px; font-size: 13px;"><b>Type of Request</th>
									<th style="width: 150px; font-size: 13px;"><b>Date Completed</th>
								</tr>
							</thead>
							<tbody>
								'.$tableData.'
							</tbody>
						</table>
					';

					return $html;
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	if(isset($_POST['start']) && isset($_POST['end'])){
		$getTable = new Functions();
		echo $getTable->generateReportPdf($_POST['start'], $_POST['end']);
		unset($getTable);
	}
?>