<?php
	require 'db_connection.php';

	Class Functions extends DbConn{
		//load admin account to table
		public function loadAdminAccounts(){
			try{
				$tableData = '';
				$tableFormat = '';
				$sql = "SELECT admin_id, admin_user, position FROM admin_info";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute()){
					foreach($stmt->fetchAll() as $rows){
						$tableData .= '
							<tr id="'.$rows['admin_id'].'">
								<td class="text-center" style="vertical-align: middle;">'.$rows['admin_user'].'</td>
								<td class="text-center" style="vertical-align: middle;">'.$rows['position'].'</td>
							</tr>
						';
					}

					$tableFormat = '
						<thead class="text-white" style="background-color: rgb(69, 176, 111);">
							<tr>
								<th class="text-center" style="vertical-align: middle;">Admin User</th>
								<th class="text-center" style="vertical-align: middle;">Position</th>
							</tr>
						</thead>
						<tbody>
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
	echo $loadTable->loadAdminAccounts();
	unset($loadTable);
?>