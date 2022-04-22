<?php
	//start sessions
	try{
		if(!@include 'includes/load_user_data_dashboard.php'){
			throw new Exception('File Error: Unable to load configuration file');
		}
		else{
			if(!isset($_SESSION['ID'])){
				header('location:index');
			}
			else{
				try{
					Class Check extends DbConn{
						public function checkMobileVerified($id){
							$sql = "SELECT isMobileVerified FROM verification WHERE resident_id = ? LIMIT 1";
							$stmt = $this->connect()->prepare($sql);
							$stmt->execute([$id]);
							$result = $stmt->fetch();

							if($result){
								if($result['isMobileVerified'] == 1){
									header('location:dashboard');
								}
							}
						}
					}

					$execute = new Check();
					$execute->checkMobileVerified($_SESSION['ID']);
					unset($execute);
				}
				catch(Exception $e){
					http_response_code(500);
					die($e->getMessage());
				}
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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="fontawesome/css/all.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<title>Verify Mobile Number</title>
	<style>
		@import 'css/dashboard.css';
	</style>
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
						<a href="dashboard" id="dashboard-btn-user" class="nav-link" style="font-size: 15px; font-weight: 500;">Dashboard</a>
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

	<!--Main container-->
	<div class="container-fluid" id="main-container">
		<div class="row justify-content-center" style="margin-top: 120px;">
			<div class="col-md-7 col-sm-10 col-12">
				<div class="card" style="border-radius: 10px">
					<div class="card-body">
						<h5 class="text-success text-center">Verify your mobile number</h5>
						<p class="text-center mt-2"><b>Your mobile number is: </p>
						<p class="text-center text-success mt-0" id="mobile-number" style="font-size: 20px;">
							<?php
								$showMobile = new Functions(); 
								echo $showMobile->getResidentData($_SESSION['ID'])['mobile_number']; 
								unset($showMobile); 
							?>
						</p>
						<div class="alert alert-success col-md-8 col-11 mx-auto" role="alert" id="alert-success-otp" style="display: none;">
							<p class="m-0">OTP successfully sent</p class="m-0">
						</div>
						<div class="input-group col-md-9 col-12 mx-auto">
							<input class="form-control" type="text" id="otp-field" placeholder="OTP Verification" maxlength="6">
							<div class="input-group-append">
								<button class="btn btn-success" id="send-otp-btn">
									Send OTP <div class="spinner-border spinner-border-sm text-light" id="spinner" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>
							</div>
							<div class="invalid-feedback" id="error-otp-field"></div>
						</div>
						<div class="input-group mx-auto col-md-5 col-12 mt-4">
							<button class="btn btn-success btn-sm form-control" id="verify-number-btn">Verify Number <div class="spinner-border spinner-border-sm text-light" id="spinner-verify" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!---Modal for success verify mobile--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-success-verify">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-check-circle"></span></h5>
						<h5 class="text-center text-white mt-2">Mobile Number Verified</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type='text/javascript' src='js/jquery-3.6.0.min.js'></script>
	<script type='text/javascript' src='js/popper.min.js'></script>
	<script type='text/javascript' src='js/bootstrap.min.js'></script>
	<script type='text/javascript' src='js/verify-mobile-number.js'></script>
	<script type='text/javascript' src='fontawesome/js/all.js'></script>
</body>
</html>