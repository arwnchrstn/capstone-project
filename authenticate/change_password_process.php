<?php
	require '../includes/db_connection.php';

	Class Functions extends DbConn{
		public function changePassword($pass, $link){
			try{
				//fetch the key from link
				$array = explode('=', $link);

				//select resident id to reset password
				$sql = "SELECT resident_login_info.resident_id, resident_login_info.password FROM resident_login_info INNER JOIN reset_password_request ON resident_login_info.resident_id = reset_password_request.resident_id WHERE reset_password_request.reset_key = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				$stmt->execute([$array[1]]);
				$result = $stmt->fetch();

				if(password_verify($pass, $result['password'])){
					return 'SAME_PASSWORD';
				}
				else{
					//hash new password
					$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

					//update the password
					$this->connect()->prepare("UPDATE resident_login_info SET password = ? WHERE resident_id = ?")->execute([$hashed_password, $result['resident_id']]);
					//delete the change password request
					$this->connect()->prepare("DELETE FROM reset_password_request WHERE reset_key = ?")->execute([$array[1]]);

					return 'SUCCESS_UPDATE';
				}
			}
			catch(PDOException|Exception $e){
				http_response_code(500);
				die($e->getMessage());
			}
		}
	}

	if(isset($_POST['password_change']) && isset($_POST['link'])){
		$reset = new Functions();
		echo $reset->changePassword($_POST['password_change'], $_POST['link']);
		unset($reset);
	}
?>