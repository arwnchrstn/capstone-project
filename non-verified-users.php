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
	<title>Non-verified Users</title>
	<style>
		@import 'css/admindashboard.css';

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
					<li class="breadcrumb-item text-success" aria-current="page" style="color: black; font-size: 20px; font-weight: 700;">Non-verified Residents</li>
				</ol>
			</nav>
			<div class="row justify-content-center">
				<div class="btn-group btn-group-toggle" data-toggle="buttons">
					<label class="btn btn-success active" id="verified-email">
						<input type="radio" name="options" checked>Verified emails
					</label>
					<label class="btn btn-success" id="unverified-email">
						<input type="radio" name="options">Unverified emails
					</label>
				</div>
			</div>
			<!---Loader--->
			<div class="row my-2 justify-content-center" id="loader" style="display: none;">
				<h5 class="m-0 ml-1 text-success">Please wait...</h5>
				<img src="resources/loader.jpg" alt="loader gif" height="25" width="25">
			</div>
			<!--Table Row-->
			<div class="row justify-content-center mb-5">
				<div class="col-11">
					<table class="table table-bordered" cellspacing="0" width="100%" id="non-verified-users-table">
						
					</table>
				</div>
			</div>
		</div>
	</div>

	<!--Success Toast-->
	<div class="position-fixed top-0 right-0 p-3" style="z-index: 999; right: 0; top: 0;">
		<div id="success-process" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1500">
		    <div class="toast-body" style="background-color: rgb(69, 176, 111); box-shadow: 6px 4px 7px 0px rgba(0,0,0,0.75);">
		    	<p id="confirm-text" class="text-white ml-1 my-1" style="font-size: 17px; font-weight: 600; margin-right: 60px;"><span class="fas fa-check-circle"></span> Account Verified</p>
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
						<div class="row mt-1 mx-3">
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

	<!---Modal for confirm logout--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-verify-confirm">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-center text-white mt-2">Are you sure you want to verify this account?</h5>
					</div>
					<div class="row mt-4 justify-content-center">
						<div class="col-4 px-1">
							<button class="btn btn-outline-light btn-sm form-control" id="no-btn" type="button" data-dismiss="modal" data-target="#modal-verify-confirm" style="font-weight: 500;">No</button>
						</div>
						<div class="col-4 px-1">
							<button class="btn btn-light btn-sm form-control" id="verify-btn" type="button" style="font-weight: 500;">Yes</button>
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
	<script type='text/javascript' src="js/non-verified-users.js"></script>
	<script type="text/javascript" src="js/datatables.min.js"></script>
	<script type="text/javascript" src="js/responsive.dataTables.min.js"></script>
</body>
</html>