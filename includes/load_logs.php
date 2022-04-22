<?php
    require 'db_connection.php';

    Class Functions extends DbConn{
        //convert date to string
		private function convertDate($ts){
			$array = explode(' ', $ts);
			return date("F", strtotime($array[0]))." ".date("d", strtotime($array[0])).", ".date("Y", strtotime($array[0]))." ".date('h:i A', strtotime($array[1]));
		}

        public function getLogs(){
            try{
                $data = array();
                $dateCompleted = '';
                $admin = '';

                $sql = "SELECT CONCAT(resident_info.first_name, ' ', IF(resident_info.middle_name='','',resident_info.middle_name) ,' ',resident_info.last_name, ' ', IF(resident_info.suffix='N/A', '', resident_info.suffix)) AS name_of_requestor, requests_list.request_id, requests_list.request_type, requests_list.purpose, requests_list.request_status, requests_list.date_completed, requests_list.admin_processed, requests_list.remarks, requests_list.request_date FROM resident_info INNER JOIN requests_list ON resident_info.resident_id = requests_list.resident_id WHERE request_status = 'COMPLETED' || request_status = 'DECLINED' || request_status = 'CANCELLED'  ORDER BY request_date ASC";

                $stmt = $this->connect()->prepare($sql);
                if($stmt->execute()){
                    foreach ($stmt->fetchAll() as $rows) {
                        switch($rows['date_completed']){
                            case '':
                                $dateCompleted = '----';
                                break;
                            default:
                                $dateCompleted = $this->convertDate($rows['date_completed']);
                                break;
                        }

                        switch($rows['admin_processed']){
                            case '':
                                $admin = '----';
                                break;
                            default:
                                $admin = $rows['admin_processed'];
                                break;
                        }

                        array_push($data, [
                            'request_id' => $rows['request_id'],
                            'name_of_requestor' => $rows['name_of_requestor'],
                            'request_type' => $rows['request_type'],
                            'purpose' => $rows['purpose'],
                            'remarks' => $rows['remarks'],
                            'date_requested' => $this->convertDate($rows['request_date']),
                            'status' => $rows['request_status'],
                            'date_completed' => $dateCompleted,
                            'admin' => $admin
                        ]);
                    }

                    return json_encode($data);
                }
                else{
                    throw new Exception('SQL_ERROR');
                }
            }
            catch(Error|Exception|PDOException $e){
                return $e->getMessage();
            }
        }
    }

    $showLogs = new Functions();
    echo $showLogs->getLogs();
?>