<?php 
	require 'db_connection.php';

	Class Functions extends DbConn{
		private function getResidentData($id){
			try{
				$sql = "SELECT picture_name FROM resident_info WHERE resident_id = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$id])){
					$results = $stmt->fetch();

					return $results;
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
		
		//check email if exist in edit informatiom
		/*public function checkEmailEdit($email){
			try{
				$sql = "SELECT email FROM resident_info WHERE email != ?";
				$stmt = $this->connect()->prepare($sql);
				$stmt->execute([$this->getResidentData($_SESSION['ID'])['email']]);
				$checker = 'false';

				foreach($stmt->fetchAll() as $rows){
					if($email == $rows['email']){
						$checker = 'true';
					}
				}

				return $checker;
			}
			catch(PDOException|Exception $e){
				http_response_code(500);
				die($e->getMessage());
			}
		}*/

		//update user info
		public function updateResidentInfo($fname, $mname, $lname, $suffix, $mobileno, $bplace, $gender, $address, $civilstat, $prof_pic){
			try{
				//check if mobile number is changed
				$sql2 = "SELECT mobile_number FROM resident_info WHERE resident_id = ? LIMIT 1";
				$stmt2 = $this->connect()->prepare($sql2);
				if($stmt2->execute([$_SESSION['ID']])){
					$result = $stmt2->fetch();
					
					if($mobileno != $result['mobile_number']){
						$stmt3 = $this->connect()->prepare("UPDATE verification SET isMobileVerified = 0 WHERE resident_id = ?");
						if(!$stmt3->execute([$_SESSION['ID']]))
							throw new Exception('UPDATE_SQL_ERROR');
					}
				}
				else
					throw new Exception('SQL_ERROR');

				//update resident info
				$sql = "UPDATE resident_info SET first_name = ?, middle_name = ?, last_name = ?, suffix = ?, mobile_number = ?, birthplace = ?, gender = ?, address = ?, civil_status = ? WHERE resident_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$fname, $mname, $lname, $suffix, $mobileno, $bplace, $gender, $address, $civilstat, $_SESSION['ID']])){
					
					//update picture on files
					$data = $prof_pic;
					$image_array1 = explode(';', $data);
					$image_array2 = explode(',', $image_array1[1]);
					$base64_decode = base64_decode($image_array2[1]);
					//fetch picture name from db
					$array = $this->getResidentData($_SESSION['ID']);
					$getPicName = $array['picture_name'];
					
					//update and delete picture in files and database
					$newPictureName = time().'.png';
					$sql3 = "UPDATE resident_info SET picture_name = ? WHERE resident_id = ?";
					$stmt3 = $this->connect()->prepare($sql3);
					if($stmt3->execute([$newPictureName, $_SESSION['ID']])){
					    unlink("../uploaded image/profile pictures/".$getPicName);
					    $img_path = "../uploaded image/profile pictures/".$newPictureName;
    					file_put_contents($img_path, $base64_decode);
    
    					throw new Exception('SUCCESS_UPDATE');
					}
					else{
					    throw new Exception('UPDATE_SQL_ERROR');
					}
				}
				else
					throw new Exception('UPDATE_SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//check email if exist in edit info
	/*if(isset($_POST['checkEmailEdit'])){
		$checkEmailEdit = new Functions();
		echo $checkEmailEdit->checkEmailEdit($_POST['checkEmailEdit']);
		unset($checkEmailEdit);
	}*/

	//update resident info
	if(isset($_POST['fname']) && isset($_POST['mname']) && isset($_POST['lname']) && isset($_POST['suffix']) && isset($_POST['bplace']) && isset($_POST['gender']) && isset($_POST['civilstat']) && isset($_POST['address']) && isset($_POST['mobileno']) && isset($_POST['pic_data'])){
		$updateRes = new Functions();
		echo $updateRes->updateResidentInfo($_POST['fname'], $_POST['mname'], $_POST['lname'], $_POST['suffix'], $_POST['mobileno'], $_POST['bplace'], $_POST['gender'], $_POST['address'], $_POST['civilstat'], $_POST['pic_data']);
		unset($updateRes);
	}
?>