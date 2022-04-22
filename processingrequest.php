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
	<title>Request in Process</title>
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
				<ol class="breadcrumb mx-2" style="background: none;">
					<li class="breadcrumb-item" id="dashboard-breadcrumb" style="color: black; font-size: 20px;" onclick="window.location.href= 'admindashboard'"><a role="button">Dashboard</a></li>
					<li class="breadcrumb-item text-success" aria-current="page" style="color: black; font-size: 20px; font-weight: 700;">Requests in Process</li>
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
					<table class="table table-bordered" cellspacing="0" width="100%" id="process-table">
						
					</table>
				</div>
			</div>
		</div>
	</div>

	<!--Success Toast-->
	<div class="position-fixed top-0 right-0 p-3" style="z-index: 999; right: 0; top: 0;">
		<div id="success-process-toast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
		    <div class="toast-body" style="background-color: rgb(69, 176, 111); box-shadow: 6px 4px 7px 0px rgba(0,0,0,0.75);">
		    	<p class="text-white ml-1 my-1" style="font-size: 17px; font-weight: 600; margin-right: 60px;">SMS and Email Sent Successfully</p>
		    </div>
		</div>
	</div>

	<!--Modal for loading process-->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-loading-process">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<div id="loader-processing" style="display: none;">
						<h5 class="text-white mt-2">Please wait... <span><img src="resources/loader.jpg" alt="loader gif" height="23" width="23"></span></h5>
					</div>
					<div id="success-process-modal" style="display: none;">
						<!---Image container--->
						<div class="icon-box">
							<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-check-circle"></span></h5>
							<h5 class="text-center text-white mt-2" id="confirm-text">Certificate Generated</h5>
						</div>
						<div class="row mt-5 justify-content-center">
							<div class="col-6">
								<a class="btn btn-light btn-sm form-control" type="button" data-dismiss="modal" data-target="#modal-loading-process" style="font-weight: 500;">Close</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!---Modal for confirm complete request--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-generate-confirm">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-center text-white mt-2">Are you sure you want to generate certificate for this resident?</h5>
					</div>
					<div class="row mt-4 justify-content-center">
						<div class="col-4 px-1">
							<button class="btn btn-outline-light btn-sm form-control" id="no-btn" type="button" data-dismiss="modal" data-target="#modal-generate-confirm" style="font-weight: 500;">No</button>
						</div>
						<div class="col-4 px-1">
							<button class="btn btn-light btn-sm form-control" id="generate-btn" type="button" style="font-weight: 500;">Yes</button>
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
	<script type='text/javascript' src="js/processingrequest.js"></script>
	<script type="text/javascript" src="js/datatables.min.js"></script>
	<script type="text/javascript" src="js/responsive.dataTables.min.js"></script>
</body>
</html>