<?php
	require 'db_connection.php';
	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;

	Class Functions extends DbConn{
		//convert date to string
		private function convertDate($ts){
			$array = explode(' ', $ts);
			return date("F", strtotime($array[0]))." ".date("d", strtotime($array[0])).", ".date("Y", strtotime($array[0]))." ".date('h:i A', strtotime($array[1]));
		}

		public function generateReportPdf(){
			try{
				date_default_timezone_set('Asia/Manila');
				$tableData = '';
				$date = date('Y-m-d h:i A');

				$sql = "SELECT CONCAT(resident_info.last_name, ', ', resident_info.first_name, ' ', IF(resident_info.middle_name = '','',resident_info.middle_name),' ', IF(resident_info.suffix = 'SR' OR resident_info.suffix = 'JR', CONCAT(resident_info.suffix, '.'), IF(resident_info.suffix='N/A','', resident_info.suffix))) as name, resident_info.resident_id, resident_info.account_created, resident_info.address, IF(verification.isAccountVerified = 1,'Verified','Not Verified') as account_status FROM resident_info INNER JOIN verification ON resident_info.resident_id = verification.resident_id ORDER BY name ASC";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute()){
					$result = $stmt->fetchAll();

					$pdf = new Dompdf();

					foreach ($result as $rows) {
						$tableData .= '
							<tr>
								<th style="width: 100px; font-weight: 400; font-size: 12px;">'.$rows['resident_id'].'</th>
								<th style="width: 150px; font-weight: 400; font-size: 12px;">'.$rows['name'].'</th>
								<th style="width: 200px; font-weight: 400; font-size: 12px;">'.$rows['address'].'</th>
								<th style="width: 150px; font-weight: 400; font-size: 12px;">'.$this->convertDate($rows['account_created']).'</th>
								<th style="width: 150px; font-weight: 400; font-size: 12px;">'.$rows['account_status'].'</th>
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
						<h6 style="text-align: center; margin: 0 0 20px 0;"><b>List of residents as of '.date("F", strtotime($date))." ".date("d", strtotime($date)).", ".date("Y", strtotime($date))." ".date('h:i A', strtotime($date)).'</h6>

						<table border="1" colspacing="0">
							<thead">
								<tr>
									<th style="width: 100px; font-size: 13px;"><b>Resident ID</th>
									<th style="width: 150px; font-size: 13px;"><b>Name</th>
									<th style="width: 200px; font-size: 13px;"><b>Address</th>
									<th style="width: 150px; font-size: 13px;"><b>Account Created</th>
									<th style="width: 150px; font-size: 13px;"><b>Account Status</th>
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
					throw new Exceptiop('SQL_ERROR');
			}
			catch(PDOException|Exception $e){
				return $e->getMessage();
			}
		}
	}

	$getTable = new Functions();
	echo $getTable->generateReportPdf();
	unset($getTable);
?>