<?php
	//start sessions
	try{
		if(!@include 'includes/db_connection.php'){
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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="fontawesome/css/all.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/requestcertificate.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<title>Request Certificates</title>
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
						<a href="requestcertificate" id="req-docs-user" class="nav-link active" style="font-size: 15px; font-weight: 500;">Request Certificates</a>
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
		<!--Request certificate form-->
		<form id="request-form" novalidate>
			<div class="form-row justify-content-center" style="margin-top: 80px">
				<div class="col-md-5 col-11">
					<div class="card" style="border: 2px solid rgb(69, 176, 111); border-radius: 10px;">
						<div class="card-body p-3">
							<h5 class="card-title text-success m-0">Please select the certificate you want to request</h5>
						</div>
					</div>
				</div>
			</div>

			<div class="form-row justify-content-center mt-2 mb-5">
				<div class="col-md-5 col-11">
					<div class="card" style="border: 2px solid rgb(69, 176, 111); border-radius: 10px;">
						<div class="card-body">
							<!--indigency-->
							<div class="custom-control custom-checkbox">
								<input class="custom-control-input" type="checkbox" name="request[]" id="select-indigency" value="INDIGENCY">
								<label class="custom-control-label" for="select-indigency"><strong>Certificate of Indigency</strong></label>
							</div>
							<div id="indigency-purpose" class="col-12 mt-1" style="display: none;">
								<label class="m-0 ml-1" for="purpose-indigency"><small style="font-weight: 500">Enter Purpose <span style="color: red;">*</span></small></label>
								<input class="form-control form-control-sm" type="email" name="purpose[]" id="purpose-indigency-field" placeholder="Purpose" maxlength="30" style="text-transform: uppercase;">
								<div class="invalid-feedback" id="purpose-indigency-error"></div>
							</div>

							<!--residency-->
							<div class="custom-control custom-checkbox mt-3">
								<input class="custom-control-input" type="checkbox" name="request[]" id="select-residency" value="RESIDENCY">
								<label class="custom-control-label" for="select-residency"><strong>Certificate of Residency</strong></label>
							</div>
							<div id="residency-purpose" class="col-12 mt-1" style="display: none;">
								<label class="m-0 ml-1" for="purpose-residency"><small style="font-weight: 500">Enter Purpose <span style="color: red;">*</span></small></label>
								<input class="form-control form-control-sm" type="email" name="purpose[]" id="purpose-residency-field" placeholder="Purpose" maxlength="30" style="text-transform: uppercase;">
								<div class="invalid-feedback" id="purpose-residency-error"></div>
							</div>

							<!--indigency-->
							<div class="custom-control custom-checkbox mt-3">
								<input class="custom-control-input" type="checkbox" name="request[]" id="select-clearance" value="CLEARANCE">
								<label class="custom-control-label" for="select-clearance"><strong>Barangay Clearance</strong></label>
							</div>
							<div id="clearance-purpose" class="col-12 mt-1" style="display: none;">
								<label class="m-0 ml-1" for="purpose-clearance"><small style="font-weight: 500">Enter Purpose <span style="color: red;">*</span></small></label>
								<input class="form-control form-control-sm" type="email" name="purpose[]" id="purpose-clearance-field" placeholder="Purpose" maxlength="30" style="text-transform: uppercase;">
								<div class="invalid-feedback" id="purpose-clearance-error"></div>
							</div>
							
							<!---Loader--->
							<div class="row my-1 justify-content-center" id="loader" style="display: none;">
								<h6 class="m-0 ml-1 text-success">Please wait...</h6>
								<img src="resources/loader.jpg" alt="loader gif" height="20" width="20">
							</div>

							<div class="col-12 mt-4">
								<button class="btn btn-success form-control form-control-sm" type="submit" id="submit-reqs">Request Certificate</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<!---Modal for success request--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-success-request">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-check-circle"></span></h5>
						<h5 class="text-center text-white mt-2">Request Successful</h5>
						<p class="text-center text-white mt-2">Please wait for the SMS or Email confirmation before you go to the barangay hall to claim your document. You can also check your request status on your account, Thank you.</p>
					</div>
					<div class="row mt-5 justify-content-center">
						<div class="col-6">
							<a class="btn btn-light btn-sm form-control" type="button" data-dismiss="modal" data-target="#modal-success-request" style="font-weight: 500;">Close</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--Modal notice-->
	<div class="modal show modal fade" id="modal-notice" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color: rgb(69, 176, 111);">
					<h5 class="text-white m-0">Notice</h5>
				</div>
				<div class="modal-body">
					<p class="text-justify" style="font-weight: 400;">Please make sure that your information is updated before you request a certificate, Thank you!</p>

					<p class="text-justify pt-3"><b><i>Note: Your request will not process if your account status is 'Not Verified', once your account is verified by the admin, your request will be processed, Thank you!</i></b></p>
					<div class="row justify-content-center mt-5">
						<div class="col-md-6 col-10">
							<button class="btn btn-success form-control form-control-sm" data-dismiss="modal" data-target="#modal-notice">I understand</button>
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
	<script type='text/javascript' src='js/requestcertificate.js'></script>
</body>
</html>