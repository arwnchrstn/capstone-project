<?php
	//Import PHPMailer classes into the global namespace
	//These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	//Load Composer's autoloader
	require '../mailer/Exception.php';
	require '../mailer/PHPMailer.php';
	require '../mailer/SMTP.php';

	require 'db_connection.php';
	require 'send_sms.php';

	Class Functions extends DbConn{
		//send email to the user
		private function sendEmail($email, $req_no, $docType){
			//Create an instance; passing `true` enables exceptions
			$mail = new PHPMailer(true);
			$additionalMessage = $docType == 'Barangay Clearance' ? 'please bring ONE (1) 1x1 picture for your '.$docType : ', ';

			try {
			    //Server settings
			    $mail->isSMTP();                                            //Send using SMTP
			    $mail->Host       = 'smtp.hostinger.com';                       //Set the SMTP server to send through
			    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			    $mail->Username   = 'bigaadocrequest@bigaadocrequest.online';            //SMTP username
			    $mail->Password   = 'BigaaDocRequest2021';                  //SMTP password
			    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable implicit TLS encryption
			    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			    //Recipients
			    $mail->setFrom('bigaadocrequest@bigaadocrequest.online', 'Bigaa Certificate Request');
			    $mail->addAddress($email);     //Add a recipient

			    //Content
			    $mail->isHTML(true);                                  //Set email format to HTML
			    $mail->Subject = 'Certificate Ready For Pickup';
			    $mail->Body    = '
						  <head>
						    <style>
						    	*{
						    		font-family: "Arial";
						    	}
						    	.wrapper{background-color: whitesmoke;}
						    	.justify-content-center{justify-content: center;}
						    	.text-center{text-align: center;}
						    	p{font-size: 14px;}
						    	h4{font-size: 24px}
						    	h5{font-size: 18px;}
						    	.text-success{color: #28a745!important;}
						    	a:hover{
						    		cursor: pointer;
						    	}
						    </style>
						  </head>
						  <body>
						      <div class="wrapper" style="padding: 50px 35px;">
						      	<div class="row">
						            <img src="https://i.ibb.co/Jdxqv7w/logo-bigaa-header.png" width="120" height="120" style="display: block; margin: 0 auto;">
						      	</div>
						        <div class="row justify-content-center" style="margin-top: 25px;">
						            <div>
						              <h4 class="text-center" style="margin-bottom: 10px;">Brgy. Bigaa, Cabuyao City</h4>
						              <h5 class="text-center" style="margin-top: 10px;">Bigaa Document Request</h5>
						            </div>
						        </div>
						        <div class="row justify-content-center mt-5">
						        	<h4 class="text-success text-center" style="margin: 30px 0 10px 0">Your certificate is ready</h4>
						        </div>
						        <div class="row justify-content-center">
						        	<div class="col-6">
						        		<p class="text-center mt-3" style="margin-bottom: 30px;">You may now claim your '.$docType.', '.$additionalMessage.'your REQUEST NUMBER is:</p>
						        	</div>
						        </div>
						        <div class="row justify-content-center">
						        	<div class="col-6">
						        		<p class="text-center text-success mt-3" style="margin-bottom: 30px; font-size: 30px;"><b>'.$req_no.'</p>
						        	</div>
						        </div>
						        <div class="row justify-content-center mt-4">
						        	<div class="col-6">
						        		<p class="text-center mt-3" style="margin-top: 30px;"><b>Thank You!</p>
						        	</div>
						        </div>
						      </div>
						  </body>
			    ';

			    if($mail->send())
			    	return 1;
			    else
			    	return 0;
			} 
			catch (Exception $e){
			    return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}

		private function convertDate($date){
			$array = explode('-', $date);

			switch($array[1]){
				case 1: return "January $array[0]";
				break;
				case 2: return "February $array[0]";
				break;
				case 3: return "March $array[0]";
				break;
				case 4: return "April $array[0]";
				break;
				case 5: return "May $array[0]";
				break;
				case 6: return "June $array[0]";
				break;
				case 7: return "July $array[0]";
				break;
				case 8: return "August $array[0]";
				break;
				case 9: return "September $array[0]";
				break;
				case 10: return "October $array[0]";
				break;
				case 11: return "November $array[0]";
				break;
				case 12: return "December $array[0]";
				break;
			}
		}

		//generate certificate of indigency
		private function generateIndigency($req_id){
			try{
				require '../fpdf183/WriteTag.php';

				//get name and address of requestor
				$requestor = '';
				$requestorAddress = '';
				$sql = "SELECT CONCAT(IF(resident_info.gender = 'MALE', 'Mr.', IF(resident_info.gender = 'FEMALE' AND resident_info.civil_status = 'SINGLE', 'Ms.',IF(resident_info.gender = 'FEMALE' AND resident_info.civil_status = 'DIVORCED', 'Ms.','Mrs.'))),' ',resident_info.first_name, ' ', IF(resident_info.middle_name='','',CONCAT(LEFT(resident_info.middle_name, 1),'. ')), resident_info.last_name, ' ', IF(resident_info.suffix='N/A', '', CONCAT(resident_info.suffix,'.'))) AS name_of_requestor, resident_info.address FROM resident_info INNER JOIN requests_list ON resident_info.resident_id = requests_list.resident_id WHERE requests_list.request_id = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$req_id])){
					//get the information of the requestor
					while($rows = $stmt->fetch()){
						$requestor = ucwords(strtolower($rows['name_of_requestor']));
						$requestorAddress = str_replace('Of','of',ucwords(strtolower($rows['address'])));
					}

					date_default_timezone_set('Asia/Manila');
					$date_today = date('Y-m-d');
					$current_date = date("F", strtotime($date_today))." ".date("j", strtotime($date_today)).", ".date("Y", strtotime($date_today));

					//init PDF_WriteTag
					$pdf = new PDF_WriteTag();
					$pdf->AddFont('Broadway','','Broadway.php');
					$pdf->AddFont('Cambria','','Cambria.php');
					$pdf->AddPage('P', 'Letter', 0);
					$pdf->SetStyle('p','Times','I',12,'0,0,0',16);
					$pdf->SetStyle('pers','Times','BI',0,'0,0,0');
					$pdf->SetFont('Times','',12);
					//header start
					$pdf->Image('../document images/bigaa-header-bg.png', 10, 11);
					$pdf->Image('../document images/logo-bigaa-header.png', 60, 13, 25, 25, 'PNG');
					$pdf->SetXY(87,13);
					$pdf->MultiCell(50,6,"Republic of the Philippines\nProvince of Laguna\nCity of Cabuyao",0,'C');
					$pdf->SetFont('Broadway','',12);
					$pdf->SetXY(90,33);
					$pdf->Cell(50,5,'BARANGAY BIGAA',0,'C');
					$pdf->SetFont('Cambria','',22);
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),43);
					$pdf->MultiCell(0,5,'OFFICE OF THE BARANGAY CHAIRMAN',0,'C');
					$pdf->SetLineWidth(0.45);
					$pdf->Line(25,51,$pdf->GetPageWidth()-25,51);
					$pdf->SetFont('Cambria','',9);
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),52);
					$pdf->MultiCell(0,3,'bigaacabuyaocity@gmail.com (049) 304-3268',0,'C');
					//header end
					//logo bg
					$pdf->Image('../document images/logo-bigaa-bg.png',$pdf->GetPageWidth()-($pdf->GetPageWidth()-43.5), 60);
					//date generated
					$pdf->SetFont('Times','I',12);
					$pdf->Text($pdf->GetPageWidth()-54.5,76,$current_date);
					//title certificate
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),86);
					$pdf->SetFont('Broadway','',26);
					$pdf->MultiCell(0,6,'C E R T I F I C A T I O N',0,'C');
					//greetings
					$pdf->SetFont('Times','BI',12);
					$pdf->Text($pdf->GetPageWidth()-191,103.5,'TO WHOM IT MAY CONCERN:');
					$pdf->SetXY($pdf->GetPageWidth()-192, 108);
					$pdf->SetLeftMargin(26);
					$pdf->SetRightMargin(26);
					//set ordinal indicator
					$ordinal_indi = '';
					$ends = array('th','st','nd','rd','th','th','th','th','th','th');
					if ((date('j',strtotime($date_today))%100) >= 11 && (date('j',strtotime($date_today))%100) <= 13)
					   $ordinal_indi = date('j',strtotime($date_today)).'th';
					else
					   $ordinal_indi = date('j',strtotime($date_today)).$ends[date('j',strtotime($date_today)) % 10];
					//document text
					$text = 
					'<p>This is to certify that <pers>'.$requestor.'</pers>, of legal age, with a postal address <pers>'.$requestorAddress.'</pers>, is considered or belongs to indigent family.</p>
					<p>This certification is being issued upon the request of <pers>'.$requestor.'</pers>, to be used for whatever legal purposes it may serve.</p>
					<p>Issued this <pers>'.$ordinal_indi.'</pers> day of <pers>'.date('F', strtotime($date_today)).' '.date('Y', strtotime($date_today)).'</pers> at the Office of the Barangay Chairman, Sangguniang Barangay Bigaa, City of Cabuyao, Laguna.</p>
					';
					$pdf->WriteTag(0,8,$text,0,'J',0,0);
					//chairman signature
					$pdf->Image('../document images/chairman-sign.png',$pdf->GetPageWidth()-79,187,47,20,'PNG');
					//chairman name
					$pdf->SetFont('Times','B',11);
					$pdf->Text($pdf->GetPageWidth()-77,200,'HON. MARIO M. SERVO');
					//charmain title
					$pdf->SetFont('Times','I',11);
					$pdf->Text($pdf->GetPageWidth()-70.5,204,'Barangay Charmain');
					//border
					$pdf->SetLineWidth(1.5);
					$pdf->Rect(10, 10, $pdf->GetPageWidth()-20, $pdf->GetPageHeight()-20, 'D');
					return 'SUCCESS_GENERATE_PDF' . base64_encode($pdf->Output('S'));
				}
				else
					return 'SQL_ERROR';
			}
			catch(PDOException|Exception|Error $e){
				return 'Oops, an error occurred.';
			}
		}

		//generate certificate of residency
		private function generateResidency($req_id){
			try{
				require '../fpdf183/WriteTag.php';

				//get info of the requestor
				date_default_timezone_set('Asia/Manila');
				$date_today = date('Y-m-d');
				$current_date = date("F", strtotime($date_today))." ".date("j", strtotime($date_today)).", ".date("Y", strtotime($date_today));
				$requestor = '';
				$requestorAddress = '';
				$requestorAge = '';
				$requestorBdate = '';
				$requestorYearOfStay = '';
				$sql = "SELECT CONCAT(IF(resident_info.gender = 'MALE', 'Mr.', IF(resident_info.gender = 'FEMALE' AND resident_info.civil_status = 'SINGLE', 'Ms.',IF(resident_info.gender = 'FEMALE' AND resident_info.civil_status = 'DIVORCED', 'Ms.','Mrs.'))),' ',resident_info.first_name, ' ', IF(resident_info.middle_name='','',CONCAT(LEFT(resident_info.middle_name, 1),'. ')), resident_info.last_name, ' ', IF(resident_info.suffix='N/A', '', CONCAT(resident_info.suffix,'.'))) AS name_of_requestor, resident_info.address, resident_info.birthdate, resident_info.year_of_stay FROM resident_info INNER JOIN requests_list ON resident_info.resident_id = requests_list.resident_id WHERE requests_list.request_id = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$req_id])){
					//get the information of the requestor
					while($rows = $stmt->fetch()){
						$requestor = ucwords(strtolower($rows['name_of_requestor']));
						$requestorAddress = str_replace('Of','of',ucwords(strtolower($rows['address'])));
						$requestorBdate = $rows['birthdate'];
						$requestorYearOfStay = $this->convertDate($rows['year_of_stay']);
					}
					$requestorAge = date_diff(date_create($date_today),date_create($requestorBdate))->format('%y');

					//init PDF_WriteTag
					$pdf = new PDF_WriteTag();
					$pdf->AddFont('Broadway','','Broadway.php');
					$pdf->AddFont('Cambria','','Cambria.php');
					$pdf->AddPage('P', 'Letter', 0);
					$pdf->SetStyle('p','Times','I',12,'0,0,0',16);
					$pdf->SetStyle('pers','Times','BI',0,'0,0,0');
					$pdf->SetFont('Times','',12);
					//header start
					$pdf->Image('../document images/bigaa-header-bg.png', 10, 11);
					$pdf->Image('../document images/logo-bigaa-header.png', 60, 13, 25, 25, 'PNG');
					$pdf->SetXY(87,13);
					$pdf->MultiCell(50,6,"Republic of the Philippines\nProvince of Laguna\nCity of Cabuyao",0,'C');
					$pdf->SetFont('Broadway','',12);
					$pdf->SetXY(90,33);
					$pdf->Cell(50,5,'BARANGAY BIGAA',0,'C');
					$pdf->SetFont('Cambria','',22);
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),43);
					$pdf->MultiCell(0,5,'OFFICE OF THE BARANGAY CHAIRMAN',0,'C');
					$pdf->SetLineWidth(0.45);
					$pdf->Line(25,51,$pdf->GetPageWidth()-25,51);
					$pdf->SetFont('Cambria','',9);
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),52);
					$pdf->MultiCell(0,3,'bigaacabuyaocity@gmail.com (049) 304-3268',0,'C');
					//header end
					//logo bg
					$pdf->Image('../document images/logo-bigaa-bg.png',$pdf->GetPageWidth()-($pdf->GetPageWidth()-43.5), 60);
					//date generated
					$pdf->SetFont('Times','I',12);
					$pdf->Text($pdf->GetPageWidth()-54.5,76,$current_date);
					//title certificate
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),86);
					$pdf->SetFont('Broadway','',26);
					$pdf->MultiCell(0,6,'C E R T I F I C A T I O N',0,'C');
					//greetings
					$pdf->SetFont('Times','BI',12);
					$pdf->Text($pdf->GetPageWidth()-191,103.5,'TO WHOM IT MAY CONCERN:');
					$pdf->SetXY($pdf->GetPageWidth()-192, 108);
					$pdf->SetLeftMargin(26);
					$pdf->SetRightMargin(26);
					//set ordinal indicator
					$ordinal_indi = '';
					$ends = array('th','st','nd','rd','th','th','th','th','th','th');
					if ((date('j',strtotime($date_today))%100) >= 11 && (date('j',strtotime($date_today))%100) <= 13)
					   $ordinal_indi = date('j',strtotime($date_today)).'th';
					else
					   $ordinal_indi = date('j',strtotime($date_today)).$ends[date('j',strtotime($date_today)) % 10];
					//document text
					$text = 
					'<p>This is certify that <pers>'.$requestor.'</pers>, <pers>'.$requestorAge.'</pers> years of age, male born on <pers>'.date("F", strtotime($requestorBdate))." ".date("j", strtotime($requestorBdate)).", ".date("Y", strtotime($requestorBdate)).'</pers> is bona fide resident of this Barangay with a postal address at <pers>'.$requestorAddress.'</pers>. This is to certify further that <pers>'.$requestor.'</pers> has been living in Barangay Bigaa, City of Cabuyao, Laguna since <pers>'.$requestorYearOfStay.' up to present</pers>.</p>
					<p>This certification is being issued upon the request of <pers>'.$requestor.'</pers>, to be used for whatever legal purposes it may serve.</p>
					<p>Issued this <pers>'.$ordinal_indi.'</pers> day of <pers>'.date('F', strtotime($date_today)).' '.date('Y', strtotime($date_today)).'</pers> at the Office of the Barangay Chairman, Sangguniang Barangay Bigaa, City of Cabuyao, Laguna.</p>
					';
					$pdf->WriteTag(0,8,$text,0,'J',0,0);
					//chairman signature
					$pdf->Image('../document images/chairman-sign.png',$pdf->GetPageWidth()-79,200,47,20,'PNG');
					//chairman name
					$pdf->SetFont('Times','B',11);
					$pdf->Text($pdf->GetPageWidth()-77,213,'HON. MARIO M. SERVO');
					//charmain title
					$pdf->SetFont('Times','I',11);
					$pdf->Text($pdf->GetPageWidth()-70.5,217,'Barangay Charmain');
					//border
					$pdf->SetLineWidth(1.5);
					$pdf->Rect(10, 10, $pdf->GetPageWidth()-20, $pdf->GetPageHeight()-20, 'D');
					return 'SUCCESS_GENERATE_PDF' . base64_encode($pdf->Output('S'));
				}
				else
					return 'SQL_ERROR';
			}
			catch(PDOException|Exception|Error $e){
				return 'Oops, an error occurred.';
			}
		}

		private function clearanceDate($currYear, $yos){
			$secInMonth = 2628288;
			$calculate_month = floor((strtotime($currYear) - strtotime($yos)) / $secInMonth);
			
			if($calculate_month < 12){
				return "$calculate_month MONTHS";
			}
			else if($calculate_month > 12){
				$calculate_year = floor($calculate_month / 12);
				$calculate_month = $calculate_month % 12;
				return "$calculate_year YEAR(S) and $calculate_month MONTH(S)";
			}
		}

		//generate barangay clearance
		private function generateClearance($req_id, $purpose){
			try{
				require '../fpdf183/WriteTag.php';

				//fetch and update the control number
				$sql = "SELECT ctrl_no_clearance FROM requests_list WHERE request_id = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$req_id])){
					$sql2 = "SELECT control_no FROM control_number WHERE control_id = 'CONTROL_NUMBER'";
					$stmt2 = $this->connect()->prepare($sql2);
					if($stmt2->execute()){
						$currentCtrlNo = $stmt2->fetch()['control_no'];

						if($stmt->fetch()['ctrl_no_clearance'] == 0){
							//update control number on request document (database)
							$stmt3 = $this->connect()->prepare("UPDATE requests_list SET ctrl_no_clearance = ? WHERE request_id = ?");
							if(!$stmt3->execute([$currentCtrlNo,$req_id]))
								throw new Exception('UPDATE_SQL_ERROR');

							//update the current control number
							$stmt4 = $this->connect()->prepare("UPDATE control_number SET control_no = ? WHERE control_id = 'CONTROL_NUMBER'");
							if(!$stmt4->execute([$currentCtrlNo+1]))
								throw new Exception('UPDATE_SQL_ERROR');
						}
					}
					else
						throw new Exception('SQL_ERROR');
				}
				else
					throw new Exception('SQL_ERROR');


				//get info of the requestor
				date_default_timezone_set('Asia/Manila');
				$date_today = date('Y-m-d');
				$current_date = date("F", strtotime($date_today))." ".date("j", strtotime($date_today)).", ".date("Y", strtotime($date_today));
				$requestor = '';
				$requestorAddress = '';
				$requestorAge = '';
				$requestorBdate = '';
				$requestorYearOfStay = '';
				$requestorBplace = '';
				$requestorCivilstat = '';
				$requestorGender = '';
				$requestorMobile = '';
				$documentCtrlNo = '';
				$sql = "SELECT CONCAT(resident_info.last_name, ', ', resident_info.first_name, ' ', IF(resident_info.suffix='N/A','',IF(resident_info.suffix='JR' OR resident_info.suffix='SR', CONCAT(resident_info.suffix,'.'),resident_info.suffix)), ' ', IF(resident_info.middle_name='','',CONCAT(LEFT(resident_info.middle_name, 1),'. '))) AS name_of_requestor, resident_info.address, resident_info.birthdate, resident_info.birthplace, resident_info.civil_status, resident_info.gender, resident_info.mobile_number, resident_info.year_of_stay, requests_list.ctrl_no_clearance FROM resident_info INNER JOIN requests_list ON resident_info.resident_id = requests_list.resident_id WHERE requests_list.request_id = ? LIMIT 1";
				$stmt = $this->connect()->prepare($sql);
				if($stmt->execute([$req_id])){
					//get the information of the requestor
					while($rows = $stmt->fetch()){
						$requestor = $rows['name_of_requestor'];
						$requestorAddress = $rows['address'];
						$requestorBdate = $rows['birthdate'];
						$requestorBplace = $rows['birthplace'];
						$requestorGender = $rows['gender'];
						$requestorCivilstat = $rows['civil_status'];
						$requestorYearOfStay = $rows['year_of_stay'];
						$requestorMobile = $rows['mobile_number'];
						$documentCtrlNo = $rows['ctrl_no_clearance'];
					}
					$requestorAge = date_diff(date_create($date_today),date_create($requestorBdate))->format('%y');

					//init PDF_WriteTag
					$pdf = new PDF_WriteTag();
					$pdf->AddFont('Broadway','','Broadway.php');
					$pdf->AddFont('Cambria','','Cambria.php');
					$pdf->AddFont('Showcard Gothic','','SHOWG.php');
					$pdf->AddPage('P', 'Letter', 0);
					$pdf->SetStyle('p','Times','',10,'0,0,0',12);
					$pdf->SetStyle('tag','Times','B',10,'0,0,0');
					$pdf->SetStyle('pers','Times','B',14,'0,0,0');
					$pdf->SetFont('Times','',12);
					//header start
					$pdf->Image('../document images/logo-bigaa-header.png', 45, 13, 27, 27, 'PNG');
					$pdf->Image('../document images/cabs-seal.png', 145, 13, 28, 28, 'PNG');
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),13);
					$pdf->MultiCell(0,6,"Republic of the Philippines\nProvince of Laguna\nCITY OF CABUYAO",0,'C');
					$pdf->SetTextColor(40,167,80);
					$pdf->SetFont('Showcard Gothic','',14);
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),33);
					$pdf->MultiCell(0,5,'BARANGAY BIGAA',0,'C');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetFont('Times','',11);
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),40);
					$pdf->MultiCell(0,5,'OFFICE OF THE BARANGAY CHAIRMAN',0,'C');
					//header end
					$pdf->SetLineWidth(0.45);
					//brgy clerance heading
					$pdf->Line(10,46,$pdf->GetPageWidth()-10,46);
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),47);
					$pdf->SetFont('Times','B',24);
					$pdf->MultiCell(0,8,'BARANGAY CLEARANCE',0,'C');
					$pdf->Line(10,55,$pdf->GetPageWidth()-10,55);
					$pdf->SetFont('Cambria','',9);
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-10),56);
					$pdf->MultiCell(0,3,'bigaacabuyaocity@gmail.com (049) 304-3268',0,'C');
					//watermark
					$pdf->SetFont('Arial','B', 50);
					$pdf->SetTextColor(220,220,220);
					$pdf->Text($pdf->GetPageWidth()/2-26, $pdf->GetPageHeight()/2,'Page 1');
					//document start
					$pdf->SetLeftMargin(20);
					$pdf->SetRightMargin($pdf->GetPageWidth()-($pdf->GetPageWidth()-20));
					$pdf->SetFont('Times','',10);
					$pdf->SetTextColor(0,0,0);
					$pdf->SetY(75);
					$pdf->Text(20,70,'TO WHOM IT MAY CONCERN');
					$text = 
					'<p>As per record kept in this office, the person whose name, right thumb print and signature appear hereon has requested a <tag>BARANGAY CLEARANCE</tag> with the following information:</p>
					';
					$pdf->WriteTag(0,6,$text,0,'J',0,0);
					//document formatting
					$pdf->SetFont('Times','',11);
					$pdf->SetLineWidth(0.2);
					//name
					$pdf->Text(20,97,'NAME');
					$pdf->Text(60,97,':');
					$pdf->Line(64,98,64+95,98);
					//address
					$pdf->Text(20,103,'ADDRESS');
					$pdf->Text(60,103,':');
					$pdf->Line(64,104,64+95,104);
					$pdf->Line(64,110,64+95,110);
					//period of stay
					$pdf->Text(20,115,'PERIOD OF STAY');
					$pdf->Text(60,115,':');
					$pdf->Line(64,116,64+40,116);
					//bdate
					$pdf->Text(20,121,'DATE OF BIRTH');
					$pdf->Text(60,121,':');
					$pdf->Line(64,122,64+40,122);
					//bplace
					$pdf->Text(20,127,'PLACE OF BIRTH');
					$pdf->Text(60,127,':');
					$pdf->Line(64,128,64+95,128);
					//civil status
					$pdf->Text(20,133,'CIVIL STATUS');
					$pdf->Text(60,133,':');
					$pdf->Line(64,134,64+40,134);
					//ctc
					$pdf->Text(104,133,'CTC NO.');
					$pdf->Text(125,133,':');
					$pdf->Text(130,132.5,'-');
					$pdf->Line(128,134,159,134);
					//age
					$pdf->Text(20,139,'AGE');
					$pdf->Text(60,139,':');
					$pdf->Line(64,140,64+40,140);
					//issued on
					$pdf->Text(104,139,'ISSUED ON');
					$pdf->Text(125,139,':');
					$pdf->Text(130,138.5,'-');
					$pdf->Line(128,140,159,140);
					//age
					$pdf->Text(20,145,'GENDER');
					$pdf->Text(60,145,':');
					$pdf->Line(64,146,64+40,146);
					//issued at
					$pdf->Text(104,145,'ISSUED AT');
					$pdf->Text(125,146,':');
					$pdf->Text(130,144.5,'-');
					$pdf->Line(128,146,159,146);
					//purposes
					$pdf->Text(20,155,'PURPOSES');
					$pdf->Text(60,155,':');
					$pdf->Line(64,156,64+130,156);
					//remarks
					$pdf->Text(20,161,'REMARKS');
					$pdf->Text(60,161,':');
					$pdf->Line(64,162,64+130,162);
					//date issued
					$pdf->Text(20,167,'DATE ISSUED');
					$pdf->Text(60,167,':');
					$pdf->Line(64,168,64+130,168);
					//mobile no
					$pdf->Text(20,173,'MOBILE NO.');
					$pdf->Text(60,173,':');
					//seal
					$pdf->Image('../document images/seal-bigaa-logo.png', 23, 195, 35, 35, 'PNG');
					$pdf->SetFont('Arial','B',9);
					$pdf->SetXY(18.5,208);
					$pdf->SetTextColor(255,0,0);
					$pdf->MultiCell(45,4,"NOT VALID\nWITHOUT OFFICIAL\nDRY SEAL",0,'C');
					//approved for issue
					$pdf->SetFont('Times','B',9);
					$pdf->SetTextColor(0,0,0);
					$pdf->Text(152,200,'APPROVED FOR ISSUE:');
					//chairman signature
					$pdf->SetTextColor(0,0,0);
					$pdf->Image('../document images/chairman-sign.png',$pdf->GetPageWidth()-70,205,47,20,'PNG');
					//chairman name
					$pdf->SetFont('Times','B',11);
					$pdf->Text($pdf->GetPageWidth()-69,218,'HON. MARIO M. SERVO');
					//charmain title
					$pdf->SetFont('Times','',11);
					$pdf->Text($pdf->GetPageWidth()-66.5,222,'PUNONG BARANGAY');
					//name of requestor
					$pdf->SetXY($pdf->GetPageWidth()-($pdf->GetPageWidth()-20),205);
					$pdf->SetFont('Times','B',12);
					$pdf->MultiCell(0,0,$requestor,0,'C');
					$pdf->Line(65,208,$pdf->GetPageWidth()-65,208);
					//requestor info
					$pdf->SetFont('Times','B',14);
					$pdf->Text(65,96.5,$requestor);		//name
					$pdf->SetFont('Times','',10);
					$pdf->SetXY(64,98);
					$pdf->MultiCell(95,6.5,$requestorAddress,0,'');		//address
					$pdf->Text(65,114.5, $this->clearanceDate($date_today, $requestorYearOfStay));		//period of stay
					$pdf->Text(65,120.5,date('F', strtotime($requestorBdate)).' '.date('j', strtotime($requestorBdate)).', '.date('Y',strtotime($requestorBdate)));		//birthdate
					$pdf->Text(65,126.5,$requestorBplace);		//birthplace
					$pdf->Text(65,132.5,$requestorCivilstat);		//civil status
					$pdf->Text(65,138.5,$requestorAge.' YEARS OLD');		//age
					$pdf->Text(65,144.5,$requestorGender);		//gender
					$pdf->SetFont('Times','B',14);
					$pdf->Text(65,154.5,$purpose);		//purpose
					$pdf->SetFont('Times','',10);
					$pdf->Text(65,160.5,'NO RECORD                                                                                 REGISTERED VOTERS');
					$pdf->Text(65,166.5,$current_date);		//date issued
					$pdf->Text(65,172.5, $requestorMobile);		//mobile number
					//control number
					$pdf->SetFont('Times','',11);
					$pdf->Text(77,220,'CONTROL NUMBER:');
					$pdf->SetXY(117,215);
					$pdf->SetFont('Times','B',11);
					$pdf->SetTextColor(255,0,0);
					$pdf->Cell(20,7,$documentCtrlNo,1,0,'C');
					//border
					$pdf->SetLineWidth(1.5);
					$pdf->Rect(10, 10, $pdf->GetPageWidth()-20, $pdf->GetPageHeight()-20, 'D');
					return 'SUCCESS_GENERATE_PDF' . base64_encode($pdf->Output('S'));
				}
				else
					return 'SQL_ERROR';
			}
			catch(PDOException|Exception|Error $e){
				return 'Oops, an error occurred.';
			}
		}
		
		//generate pdf of requested certificate
		public function generatePdf($req_id){
			try{
				$docType = '';

				//generate pdf according to request type
				$sql2 = "SELECT request_type, purpose FROM requests_list WHERE request_id = ? LIMIT 1";
				$stmt2 = $this->connect()->prepare($sql2);
				if($stmt2->execute([$req_id])){
					while($rows = $stmt2->fetch()){
						switch($rows['request_type']){
							case 'INDIGENCY':
								$docType = $this->generateIndigency($req_id);
								break;
							case 'RESIDENCY':
								$docType = $this->generateResidency($req_id);
								break;
							case 'CLEARANCE':
								$docType = $this->generateClearance($req_id, $rows['purpose']);
								break;
						}
					}
					$this->sendNotification($req_id);
					return $docType;
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}

		//send sms and email notification to the user and change the request status
		public function sendNotification($req_id){
			try{
				$email = '';
				$mobileno = '';
				$req_no = '';
				$id = '';
				$docType = '';
				
				//update the request
				$sql = "UPDATE requests_list SET request_status = ?, remarks = ? WHERE request_id = ?";
				$stmt = $this->connect()->prepare($sql);
				if(!$stmt->execute(['FOR PICKUP', 'Your certificate is ready, please pickup your document at the barangay hall', $req_id]))
					throw new Exception('UPDATE_SQL_ERROR');

				//fetch the needed info for sending
				$sql2 = "SELECT resident_info.resident_id, resident_info.mobile_number, resident_login_info.email FROM resident_info INNER JOIN resident_login_info ON resident_info.resident_id = resident_login_info.resident_id INNER JOIN requests_list ON requests_list.resident_id = resident_login_info.resident_id WHERE requests_list.request_id = ? LIMIT 1";
				$stmt2 = $this->connect()->prepare($sql2);
				if($stmt2->execute([$req_id])){
					//get the request type
					$sql3 = "SELECT request_type FROM requests_list WHERE request_id = ? LIMIT 1";
					$stmt3 = $this->connect()->prepare($sql3);
					if($stmt3->execute([$req_id])){
						while($rows = $stmt3->fetch()){
							switch($rows['request_type']){
								case 'INDIGENCY':
									$docType = 'Certificate of Indigency';
									break;
								case 'RESIDENCY':
									$docType = 'Certificate of Residency';
									break;
								case 'CLEARANCE':
									$docType = 'Barangay Clearance';
									break;
							}
						}
					}
					else
						throw new Exception('SQL_ERROR');

					//fetch data
					while($rows = $stmt2->fetch()){
						$email = $rows['email'];
						$mobileno = $rows['mobile_number'];
						$id = $rows['resident_id'];
					}

					//request number
					$array = explode('_', $req_id);
					$req_no = $array[1];

					//check if mobile number is verified
					$sql3 = "SELECT isMobileVerified FROM verification WHERE resident_id = ? LIMIT 1";
					$stmt3 = $this->connect()->prepare($sql3);
					if($stmt3->execute([$id])){
						$result = $stmt3->fetch();

						if($result){
							if($result['isMobileVerified'] == 0){
								//send email only if mobile is not verified
								if($this->sendEmail($email, $req_no, $docType) == 1)
									return 'SUCCESS_SEND';
								else
									return 'SEND FAILED: MAILER_ERROR';
							}
							else if($result['isMobileVerified'] == 1){
								//send both email and sms when mobile is verified
								if(sendSMS($mobileno, $req_no, $docType) == 1){
									if($this->sendEmail($email, $req_no, $docType) == 1)
										return 'SUCCESS_SEND';
									else
										return 'SMS_ONLY_SENT';
								}
								else if($this->sendEmail($email, $req_no, $docType) == 1){
									if(sendSMS($mobileno, $req_no, $docType) == 1)
										return 'SUCCESS_SEND';
									else
										return 'EMAIL_ONLY_SENT';
								}
								else
									return 'SEND_FAILED';
							}
						}
						else
							throw new Exception('SEND_FAILED');
					}
					else
						throw new Exception('SQL_ERROR');
				}
				else
					throw new Exception('SQL_ERROR');
			}
			catch(PDOException|Exception|Error $e){
				return $e->getMessage();
			}
		}
	}

	//generating pdf for the certificate (Admin)
	if(isset($_POST['request_id_pdf'])){
		$generateCertificate = new Functions();
		echo $generateCertificate->generatePdf($_POST['request_id_pdf']);
		unset($generateCertificate);
	}

	//send sms and email to the user (Admin)
	if(isset($_POST['request_id_sms'])){
		$sendNotif = new Functions();
		echo $sendNotif->sendNotification('FOR PICKUP', $_POST['request_id_sms']);
		unset($sendNotif);
	}
?>