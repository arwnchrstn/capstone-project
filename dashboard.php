<?php
	try{
		if(!@include 'includes/load_user_data_dashboard.php'){
			throw new Exception('File Error: Unable to load configuration file');
		}
		else{
			if(!isset($_SESSION['ID'])){
				header('location:index');
			}
		}
	}
	catch(Exception $e){
		http_response_code(404);
		die($e->getMessage());
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv=”Pragma” content=”no-cache”>
    <meta http-equiv=”Expires” content=”-1″>
    <meta http-equiv=”CACHE-CONTROL” content=”NO-CACHE”>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="fontawesome/css/all.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dashboard.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<title>Dashboard</title>
</head>
<body>
	<!---Navbar--->
	<div class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: rgb(69, 176, 111);">
		<div class="container-md">
			<a class="navbar-brand m-0" style="font-size: 16px; font-weight: 500;">
				<img src="resources/brgy-logo.png" width="25" height="25" class="d-inline-block align-top" alt="brgy-logo">
				<span id="navbar-title">Bigaa Certificate Request</span>
			</a>
			<button class="navbar-toggler btn-sm" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarResponsive">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item mx-auto" id="navbar-title-toggle">
						<a class="navbar-brand m-0" style="font-size: 16px; font-weight: 500;">
							<span >Bigaa Certificate Request</span>
						</a>
					</li>
					<li class="nav-item ml-1">
						<a href="dashboard" id="dashboard-btn-user" class="nav-link active" style="font-size: 15px; font-weight: 500;">Dashboard</a>
					</li>
					<li class="nav-item ml-1">
						<a href="requestcertificate" id="req-docs-user" class="nav-link" style="font-size: 15px; font-weight: 500;">Request Certificates</a>
					</li><li class="nav-item ml-1">
						<a href="requesthistory" id="req-history-user" class="nav-link" style="font-size: 15px; font-weight: 500;">Request History</a>
					</li>
					<li class="nav-item ml-1">
						<a id="logout-btn" class="nav-link" type="button" style="font-size: 15px; font-weight: 500;">Logout <span class="fas fa-sign-out-alt"></span></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!---Main Container--->
	<div class="container-fluid" id="main-container">
		<div class="row justify-content-center" style="margin-top: 85px;">
			<!---First section--->
			<div class="col-md-4">
				<div class="card mb-2" id="first-pane">
					<div class="card-body">
							<?php
    							$res_info = new Functions();
    							$array = $res_info->getResidentData($_SESSION['ID']);
    							echo '<div class="row justify-content-center mt-3">
    								<img src="uploaded image/profile pictures/'.$array['picture_name'].'" alt="user-photo-dashboard" width="150" height="150" style="border: 3px solid rgb(69, 176, 111); border-radius: 5px;">
    							</div>';
    							unset($res_info);
					    	?>
						<div class="row justify-content-center mt-3">
							<a href="editinformation" class="btn text-center text-white btn-success btn-sm" type="button" id="update-photo-btn" style="font-weight: 500;">Edit Information <span class="fas fa-edit"></span></a>
						</div>
						<div class="row justify-content-center mt-1">
							<a class="btn text-center text-success" type="button" id="view-voters-btn" data-toggle="modal" data-target="#modal-show-voters" style="font-weight: 500;">View Voter's ID/Certificate <span class="fas fa-eye"></span></a>
						</div>
					</div>
				</div>
				<div class="card mb-md-4 mb-2" id="status-pane">
					<div class="card-body">
						<!--card for account verification-->
						<?php
							$accountStat = new Functions();
							if($accountStat->accountStatus($_SESSION['ID']) == 0){
								echo '
									<div class="card-body" style="border-left: 5px solid #dc3545; border-radius: 5px; box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.4);" id="verify-mobile">
										<h5 class="text-center">Account Status: <span class="text-danger">Not Verified </span><span class="fas fa-times-circle text-danger"></span></h5>
										<p class="text-muted text-center m-0" id="acct-verify-text">Please wait for the admin to review your information and verify your account</p>
									</div>
								';
							}
							else if($accountStat->accountStatus($_SESSION['ID']) == 1){
								echo '
									<div class="card-body" style="border-left: 5px solid #28a745; border-radius: 5px; box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.4);" id="verify-mobile">
										<h5 class="text-center">Account Status: <span class="text-success">Verified </span><span class="fas fa-check-circle text-success"></span></h5>
										<p class="text-muted text-center m-0" id="acct-verify-text">Admin has verified your account</p>
									</div>
								';
							}

							if($accountStat->isMobileVerified($_SESSION['ID']) == 0){
								echo '
								<!--card for mobile verification-->
								<div class="card-body mt-3" style="border-left: 5px solid #ffc107; border-radius: 5px; box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.4);" id="verify-mobile">
									<h6 class="text-center"><span class="fas fa-exclamation-triangle text-warning"></span> Your mobile number is not verified</h6>
									<p class="text-muted text-center m-0" id="mobile-verify-text">Please verify your mobile number to receive notifications regarding with your request</p>
									<div class="row justify-content-center">
										<a href="verify-mobile-number" class="text-warning py-0 pt-1" style="font-weight: 500; font-size: 18px; text-decoration: none!important;">Verify Now <span class="fas fa-arrow-right"></span></a>
									</div>
								</div>
								';
							}
							unset($accountStat);
						?>
					</div>
				</div>
			</div>
			<!---Second section--->
			<div class="col-md-7 p-md-0">
				<div class="card mb-5" id="second-pane">
					<div class="card-header bg-white" style="border-radius: 5px 5px 0 0;">
						<h5 class="text-success m-0">Resident Information</h5>
					</div>
					<div class="card-body pt-1">
						<?php
							$res_info = new Functions();
							$array = $res_info->getResidentData($_SESSION['ID']);
							echo '<div class="row mt-1">
								<div class="col-md-4 px-1">
									<label class="m-0 ml-1" for="display-fname"><small style="font-weight: 500">First Name</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['first_name'].'" id="display-fname" readonly>
								</div>
								<div class="col-md-3 px-1">
									<label class="m-0 ml-1" for="display-mname"><small style="font-weight: 500">Middle Name</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['middle_name'].'" id="display-mname" readonly>
								</div>
								<div class="col-md-3 px-1">
									<label class="m-0 ml-1" for="display-lname"><small style="font-weight: 500">Last Name</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['last_name'].'" id="display-lname" readonly>
								</div>
								<div class="col-md-2 px-1">
									<label class="m-0 ml-1" for="display-suffix"><small style="font-weight: 500">Suffix</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['suffix'].'" id="display-suffix" readonly>
								</div>
							</div>
							<div class="row mt-md-4">';

							$array_bday = explode('-', $array['birthdate']);
							$string_bday = "";
							switch ($array_bday[1]) {
								case 1:
									$string_bday = 'JANUARY '.$array_bday[2].', '.$array_bday[0];
									break;
								case 2:
									$string_bday = 'FEBRUARY '.$array_bday[2].', '.$array_bday[0];
									break;
								case 3:
									$string_bday = 'MARCH '.$array_bday[2].', '.$array_bday[0];
									break;
								case 4:
									$string_bday = 'APRIL '.$array_bday[2].', '.$array_bday[0];
									break;
								case 5:
									$string_bday = 'MAY '.$array_bday[2].', '.$array_bday[0];
									break;
								case 6:
									$string_bday = 'JUNE '.$array_bday[2].', '.$array_bday[0];
									break;
								case 7:
									$string_bday = 'JULY '.$array_bday[2].', '.$array_bday[0];
									break;
								case 8:
									$string_bday = 'AUGUST '.$array_bday[2].', '.$array_bday[0];
									break;
								case 9:
									$string_bday = 'SEPTEMBER '.$array_bday[2].', '.$array_bday[0];
									break;
								case 10:
									$string_bday = 'OCTOBER '.$array_bday[2].', '.$array_bday[0];
									break;
								case 11:
									$string_bday = 'NOVEMBER '.$array_bday[2].', '.$array_bday[0];
									break;
								case 12:
									$string_bday = 'DECEMBER '.$array_bday[2].', '.$array_bday[0];
									break;
							}

							$array_yos = explode('-', $res_info->getResidentData($_SESSION['ID'])['year_of_stay']);
							$yearofstay = '';
							switch($array_yos[1]){
								case 1: $yearofstay = "January $array_yos[0]";
								break;
								case 2: $yearofstay = "February $array_yos[0]";
								break;
								case 3: $yearofstay = "March $array_yos[0]";
								break;
								case 4: $yearofstay = "April $array_yos[0]";
								break;
								case 5: $yearofstay = "May $array_yos[0]";
								break;
								case 6: $yearofstay = "June $array_yos[0]";
								break;
								case 7: $yearofstay = "July $array_yos[0]";
								break;
								case 8: $yearofstay = "August $array_yos[0]";
								break;
								case 9: $yearofstay = "September $array_yos[0]";
								break;
								case 10: $yearofstay = "October $array_yos[0]";
								break;
								case 11: $yearofstay = "November $array_yos[0]";
								break;
								case 12: $yearofstay = "December $array_yos[0]";
								break;
							}

							echo '<div class="col-md-6 px-1">
									<label class="m-0 ml-1" for="display-bdate"><small style="font-weight: 500">Birthdate</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$string_bday.'" id="display-bdate" readonly>
								</div>';


							echo '<div class="col-md-6 px-1">
									<label class="m-0 ml-1" for="display-bplace"><small style="font-weight: 500">Birthplace</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['birthplace'].'" id="display-bplace" readonly>
								</div>
							</div>
							<div class="row mt-md-4">
								<div class="col-md-4 px-1">
									<label class="m-0 ml-1" for="display-gender"><small style="font-weight: 500">Gender</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['gender'].'" id="display-gender" readonly>
								</div>
								<div class="col-md-4 px-1">
									<label class="m-0 ml-1" for="display-civilstat"><small style="font-weight: 500">Civil Status</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['civil_status'].'" id="display-civilstat" readonly>
								</div>
								<div class="col-md-4 px-1">
									<label class="m-0 ml-1" for="display-civilstat"><small style="font-weight: 500">Year of Stay</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$yearofstay.'" id="display-civilstat" readonly>
								</div>
							</div>
							<div class="row mt-md-4">
								<div class="col-md-12 px-1">
									<label class="m-0 ml-1" for="display-address"><small style="font-weight: 500">Full Address</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['address'].'" id="display-address" readonly>
								</div>
							</div>
							<div class="row mt-md-4">
								<div class="col-md-6 px-1">
									<label class="m-0 ml-1" for="display-email"><small style="font-weight: 500">Email Address</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['email'].'" id="display-email" readonly>
								</div>
								<div class="col-md-6 px-1">
									<label class="m-0 ml-1" for="display-mnumber"><small style="font-weight: 500">Mobile Number</small></label>
									<input type="text" class="form-control form-control-sm" value="'.$array['mobile_number'].'" id="display-mnumber" readonly>
								</div>
							</div>';
							unset($res_info);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!---Modal for showing voters ID--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" id="modal-show-voters" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color: rgb(69, 176, 111);">
					<h5 class="text-white m-0">Voter's ID/Certificate</h5>
					<a class="close text-white" type="button" data-dismiss="modal" data-target="#modal-show-voters"><span>&times;</span></a>
				</div>
				<div class="modal-body">
					<div class="row justify-content-center">
						<?php
							$res_info = new Functions();
							$array = $res_info->getResidentData($_SESSION['ID']);
							echo '<img src="uploaded image/voters id pictures/'.$array['voters_picture_name'].'" alt="voters-id-img" style="border: 2px solid rgb(69, 176, 111); max-width: 95%; max-height: 500px;">';
							unset($res_info);
						?>
					</div>
					<div class="row justify-content-center mt-4">
						<div class="col-5">
							<a class="btn btn-success btn-sm form-control" type="button" data-dismiss="modal" data-target="#modal-show-voters">Close</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type='text/javascript' src='js/jquery-3.6.0.min.js'></script>
	<script type='text/javascript' src='js/popper.min.js'></script>
	<script type='text/javascript' src='js/bootstrap.min.js'></script>
	<script type='text/javascript' src='fontawesome/js/all.js'></script>
	<script type='text/javascript' src="js/dashboard.js"></script>
</body>
</html>