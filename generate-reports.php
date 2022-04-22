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
	<link rel="stylesheet" href="jquery-ui-1.12.1.custom/jquery-ui.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<style>
		@import 'css/admindashboard.css';

		#dashboard-breadcrumb:hover{
			color: #28a745 !important;
			transition: ease-in 0.1s;
		}
	</style>
	<title>Generate Reports</title>
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
				<ol class="breadcrumb mx-2 my-0" style="background: none;">
					<li class="breadcrumb-item text-success" aria-current="page" style="color: black; font-size: 20px; font-weight: 700;">Generate Reports</li>
				</ol>
			</nav>
			<div class="row ml-1 mr-1">
				<!--List of residents report-->
				<div class="col-6">
					<div class="card" id="resident-reports" style="background-color: whitesmoke;">
						<h5 class="text-center text-success mt-3">List of Residents Report</h5>
						<div class="mx-auto" id="button-holder">
							<button class="btn btn-success my-3" id="check-resident-record-btn">Check Records <div class="spinner-border spinner-border-sm text-light" id="spinner-check-records" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>
						</div>
						<h6 class="text-center text-success mb-3 mx-5" id="alert-resident-record" style="display: none;"></h6>
					</div>
				</div>
				<!--Transaction report-->
				<div class="col-6">
					<div class="card" id="transaction-reports" style="background-color: whitesmoke;">
						<h5 class="text-center text-success mt-3">Completed Transactions Report</h5>
						<div class="row justify-content-center">
							<div class="col-10">
								<label class="m-0 ml-1 mt-3" for="start-date-field"><h6 style="font-weight: 500">Start date</h6></label>
								<input class="form-control form-control-sm" type="text" id="start-date-field" name="start-date" placeholder="Start date" readonly style="background-color: white;">
								<div class="invalid-feedback" id="start-date-error"></div>
							</div>
							<div class="col-10">
								<label class="m-0 ml-1 mt-3" for="end-date-field"><h6 style="font-weight: 500">End date</h6></label>
								<input class="form-control form-control-sm" type="text" id="end-date-field" name="end-date" placeholder="End date" readonly style="background-color: white;">
								<div class="invalid-feedback" id="end-date-error"></div>
							</div>
							<div id="button-holder-transact">
								<button class="btn btn-success my-3" id="check-transact-record-btn">Check Records <div class="spinner-border spinner-border-sm text-light" id="spinner-check-transact" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>
							</div>
							<h6 class="text-center text-success mb-3 mx-5" id="alert-transact-record" style="display: none;"></h6>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--modal for success generate of reports-->
	<div class="modal show modal fade" role="dialog" id="modal-success-generate" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<div class="icon-box">
						<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-check-circle"></span></h5>
						<h5 class="text-center text-white mt-2" id="confirm-text">Report Generated</h5>
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
	<script type='text/javascript' src="js/generate-reports.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript" src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>
</body>
</html>