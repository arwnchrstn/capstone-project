<?php
	try{
		if(!@include 'includes/load_admin_name.php'){
			throw new Exception('File Error: Unable to load configuration file');
		}
		else{
			if(!isset($_SESSION['ADMIN_ID'])){
				header('location: admin');
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
	<link rel="stylesheet" href="css/datatables.min.css">
	<link rel="stylesheet" href="css/responsive.dataTables.min.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<title>Verified Users</title>
	<style>
		@import 'css/admindashboard.css';

		.tooltip-container{
			position: relative;
		}

		.tooltiptext{
			display: none;
			position: absolute;
			background-color: rgba(0, 0, 0, 0.8);
			top: -100%;
			left: calc(-150% + 50%);
			padding: 5px 10px;
			white-space: nowrap;
			font-size: 15px;
			z-index: 999;
			border-radius: 5px;
		}

		.tooltip-container:hover .tooltiptext{
			display: block;
		}

		.col-9:hover{
			cursor: pointer;
			color: rgb(69, 176, 111);;
			transition: 0.15s ease-in;
		}

		#dashboard-breadcrumb:hover{
			color: #28a745 !important;
			transition: ease-in 0.1s;
		}
	</style>
</head>
<body>
	<div class="show-screen-error">
		<div class="row h-100 justify-content-center align-items-center">
			<div class="col-6">
				<h4 class="text-success text-center">Sorry, you can't access this on a small screen</h4>
			</div>
		</div>
	</div>
	<div class="wrapper">
		<div class="sidebar-nav">
			<?php include 'includes/sidebar-nav.php'; ?>
		</div>
		<div class="main-panel pr-0">
			<div class="row">
				<div class="col-6 pr-0">
					<div class="card mx-2 mt-2" style="border-radius: 8px;">
						<div class="card-header" style="background-color: whitesmoke; border-radius: 8px; height: 110px;">
							<h5 class="m-0 text-center text-success mb-2 mt-2">Barangay Bigaa Online Certificate Request</h5>
							<h5 class="m-0 text-center">Welcome, <?php $displayUser = new Functions(); echo $displayUser->displayUser($_SESSION['ADMIN_ID']); unset($displayUser); ?>!</h5>
						</div>
					</div>
				</div>
				<div class="col-6 pl-0">
					<div class="card mx-2 mt-2" style="border-radius: 8px;">
						<div class="card-header" style="background-color: whitesmoke; border-radius: 8px; height: 110px;">
							<h5 class="m-0 text-center text-success">Today is</h5>
							<h5 class="m-0 text-center mt-2" id="date-today"></h5>
							<h5 class="m-0 text-center mt-2" id="current-time"></h5>
						</div>
					</div>
				</div>
			</div>
			<nav aria-label="breadcrumb" >
				<ol class="breadcrumb mx-2 mb-1" style="background: none;">
					<li class="breadcrumb-item" id="dashboard-breadcrumb" style="color: black; font-size: 20px;" onclick="window.location.href= 'admindashboard'"><a role="button">Dashboard</a></li>
					<li class="breadcrumb-item text-success" aria-current="page" style="color: black; font-size: 20px; font-weight: 700;">Verified Residents</li>
				</ol>
			</nav>
			<!---Loader--->
			<div class="row my-2 justify-content-center" id="loader" style="display: none;">
				<h5 class="m-0 ml-1 text-success">Please wait...</h5>
				<img src="resources/loader.jpg" alt="loader gif" height="25" width="25">
			</div>
			<!--Table Row-->
			<div class="row justify-content-center mb-5">
				<div class="col-11">
					<table class="table table-bordered" cellspacing="0" width="100%" id="verified-users-table">
						
					</table>
				</div>
			</div>
		</div>
	</div>

	<!--Modal for verify process-->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" id="modal-loading-verify">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<div id="loader-verify" style="display: none;">
						<h5 class="text-white mt-2">Please wait... <span><img src="resources/loader.jpg" alt="loader gif" height="23" width="23"></span></h5>
					</div>
					<div id="success-verify" style="display: none;">
						<!---Image container--->
						<div class="icon-box">
							<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-check-circle"></span></h5>
							<h5 class="text-center text-white mt-2" id="confirm-text">User Verified</h5>
						</div>
						<div class="row mt-5 justify-content-center">
							<div class="col-6">
								<a class="btn btn-light btn-sm form-control" type="button" data-dismiss="modal" data-target="#modal-success-process" style="font-weight: 500;">Close</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!----Modal for view info---->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-view-info">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color: rgb(69, 176, 111);">
					<h5 class="text-white m-0">Resident Information</h5>
				</div>
				<div class="body">
					<!---Loader--->
					<div class="row my-3 justify-content-center" id="loader-view-info" style="display: none;">
						<h5 class="m-0 ml-1 text-success">Please wait...</h5>
						<img src="resources/loader.jpg" alt="loader gif" height="25" width="25">
					</div>
					<div id="display-info" style="display: none;">
						<div class="row mt-4 justify-content-center">
							<h5 class="text-center text-success">Resident Photo</h5>
						</div>
						<div class="row mt-1 justify-content-center">
							<img src="" alt="resident-photo" id="resident-photo" width="150" height="150" style="border: 3px solid rgb(69, 176, 111);">
						</div>
						<div class="row mt-3 mx-3">
							<div class="col-12">
								<span><b>Full Name</b>&nbsp;: <span id="full-name">&nbsp;</span>
							</div>
						</div>
						<div class="row mt-3 mx-3">
							<div class="col-12">
								<span><b>Birthdate</b>&nbsp;: <span id="birthdate">&nbsp;</span></span>
							</div>
						</div>
						<div class="row mt-3 mx-3">
							<div class="col-12">
								<span><b>Birthplace</b>&nbsp;: <span id="birthplace">&nbsp;</span></span>
							</div>
						</div>
						<div class="row mt-3 mx-3">
							<div class="col-12">
								<span><b>Gender</b>&nbsp;: <span id="gender">&nbsp;</span></span>
							</div>
						</div>
						<div class="row mt-3 mx-3">
							<div class="col-12">
								<span><b>Civil Status</b>&nbsp;: <span id="civil-status">&nbsp;</span></span>
							</div>
						</div>
						<div class="row mt-3 mx-3">
							<div class="col-12">
								<span><b>Address</b>&nbsp;: <span id="address">&nbsp;</span></span>
							</div>
						</div>
						<div class="row mt-3 mx-3">
							<div class="col-12">
								<span><b>Year of stay</b>&nbsp;: <span id="YOS">&nbsp;</span></span>
							</div>
						</div>
						<div class="row mt-3 mx-3">
							<div class="col-12">
								<span><b>Mobile Number</b>&nbsp;: <span id="mobile-number">&nbsp;</span></span>
							</div>
						</div>
						<div class="row mt-3 mx-3">
							<div class="col-12">
								<span><b>Email address</b>&nbsp;: <span id="email-address">&nbsp;</span></span>
							</div>
						</div>
						<div class="row mt-4 justify-content-center">
							<h5 class="text-center text-success">Voter's ID/Certificate</h5>
						</div>
						<div class="row mt-1 justify-content-center">
							<img src="" alt="resident-voters" id="resident-voters" style="border: 3px solid rgb(69, 176, 111); max-width: 90%; max-height: 500px;">
						</div>
						<div class="row mt-5 mb-3 mx-3 justify-content-center">
							<div class="col-5 pr-2">
								<button class="btn btn-success btn-sm form-control" type="button" data-dismiss="modal" data-target="#modal-view-info">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--Modal for generate certificate-->
	<div class="modal show modal fade" tabindex="-1" role="dialog" id="generate-cert-modal" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color: rgb(69, 176, 111);">
					<h5 class="text-white m-0">Generate Certificate</h5>
					<button class="close btn" data-toggle="modal" data-target="#generate-cert-modal">
						<span class="text-white">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!--Indigency-->
					<div class="row justify-content-center mx-1 mb-2">
						<div class="col-3" style="border: 2px solid rgb(69, 176, 111); border-radius: 10px 0 0 10px;">
							<img src="resources/indigency-icon.png" alt="Indigency image" width="70%" style="display: block; margin: 0 auto;">
						</div>
						<div class="col-9" id="indigency-col" style="background-color: rgba(230, 230, 230, 0.5); border-radius: 0 10px 10px 0; position: relative;">
							<h5 class="ml-2" style="position: absolute; top: 50%; transform: translateY(-50%); font-size: 18px;"><i>Certificate of Indigency</i></h5>
							<span class="fas fa-angle-right" style=" position: absolute; top: 50%; transform: translateY(-50%); left: 90%; font-size: 25px;"></span>
						</div>
						<div class="col-12 p-0 mt-2" id="generate-indigency" style="display: none;">
							<button class="btn-success form-control form-control-sm" id="generate-indigency-btn" style="box-shadow: 2px 3px 5px -2px rgba(0,0,0,0.75);">Generate Certificate <div class="spinner-border spinner-border-sm text-light" id="spinner-generate-indigency" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>
						</div>
					</div>
					<!--Residency-->
					<div class="row justify-content-center mx-1 mb-2">
						<div class="col-3" style="border: 2px solid rgb(69, 176, 111); border-radius: 10px 0 0 10px;">
							<img src="resources/residency-icon.png" alt="Residency image" width="70%" style="display: block; margin: 0 auto;">
						</div>
						<div class="col-9" id="residency-col" style="background-color: rgba(230, 230, 230, 0.5); border-radius: 0 10px 10px 0; position: relative;">
							<h5 class="ml-2" style="position: absolute; top: 50%; transform: translateY(-50%); font-size: 18px;"><i>Certificate of Residency</i></h5>
							<span class="fas fa-angle-right" style=" position: absolute; top: 50%; transform: translateY(-50%); left: 90%; font-size: 25px;"></span>
						</div>
						<div class="col-12 p-0 mt-2" id="generate-residency" style="display: none;">
							<button class="btn-success form-control form-control-sm" id="generate-residency-btn" style="box-shadow: 2px 3px 5px -2px rgba(0,0,0,0.75);">Generate Certificate <div class="spinner-border spinner-border-sm text-light" id="spinner-generate-residency" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>
						</div>
					</div>
					<!--Clearance-->
					<div class="row justify-content-center mx-1">
						<div class="col-3" style="border: 2px solid rgb(69, 176, 111); border-radius: 10px 0 0 10px;">
							<img src="resources/residency-icon.png" alt="Clearance image" width="70%" style="display: block; margin: 0 auto;">
						</div>
						<div class="col-9" id="clearance-col" style="background-color: rgba(230, 230, 230, 0.5); border-radius: 0 10px 10px 0; position: relative;">
							<h5 class="ml-2" style="position: absolute; top: 50%; transform: translateY(-50%); font-size: 18px;"><i>Barangay Clearance</i></h5>
							<span class="fas fa-angle-right" style=" position: absolute; top: 50%; transform: translateY(-50%); left: 90%; font-size: 25px;"></span>
						</div>
						<div class="col-12 p-0 mt-2" id="purpose-clearance" style="display: none;">
							<input type="text" id="purpose-clearance-field" class="form-control form-control-sm" placeholder="Purpose" maxlength="256" style="text-transform: uppercase;">
							<div class="invalid-feedback" id="error-purpose-clearance"></div>
							<button class="btn-success form-control form-control-sm mt-2" id="generate-clearance-btn" style="box-shadow: 2px 3px 5px -2px rgba(0,0,0,0.75);">Generate Certificate <div class="spinner-border spinner-border-sm text-light" id="spinner-generate-clearance" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!--Modal for success process-->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-success-generate">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-check-circle"></span></h5>
						<h5 class="text-center text-white mt-2" id="confirm-text">Done</h5>
					</div>
					<div class="row mt-5 justify-content-center">
						<div class="col-6">
							<a class="btn btn-light btn-sm form-control" type="button" data-dismiss="modal" data-target="#modal-success-generate" style="font-weight: 500;">Close</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!---Modal for confirm logout--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-logout-confirm">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-center text-white mt-2">Are you sure you want to logout?</h5>
					</div>
					<div class="row mt-4 justify-content-center">
						<div class="col-4 px-1">
							<button class="btn btn-outline-light btn-sm form-control" id="no-btn" type="button" data-dismiss="modal" data-target="#modal-logout-confirm" style="font-weight: 500;">No</button>
						</div>
						<div class="col-4 px-1">
							<button class="btn btn-light btn-sm form-control" id="logout-btn" type="button" style="font-weight: 500;">Yes</button>
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
	<script type='text/javascript' src="js/verified-users.js"></script>
	<script type="text/javascript" src="js/datatables.min.js"></script>
	<script type="text/javascript" src="js/responsive.dataTables.min.js"></script>
</body>
</html>