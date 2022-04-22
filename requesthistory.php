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
	<link rel="stylesheet" href="css/requesthistory.css">
	<link rel="stylesheet" href="css/datatables.min.css">
	<link rel="stylesheet" href="css/responsive.dataTables.min.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<title>Request History</title>
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
						<a href="requesthistory" id="req-history-user" class="nav-link active" style="font-size: 15px; font-weight: 500;">Request History</a>
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
			<div class="col-11">
				<h4 class="text-center text-success">Request History</h4>
			</div>
		</div>
		<!---Loader--->
		<div class="row my-2 justify-content-center" id="loader" style="display: none;">
			<h5 class="m-0 ml-1 text-success">Please wait...</h5>
			<img src="resources/loader.jpg" alt="loader gif" height="25" width="25">
		</div>
		<div class="row justify-content-center mb-5">
			<div class="col-11">
				<table class="table table-bordered" cellspacing="0" width="100%" id="request-table">
					
				</table>
			</div>
		</div>
	</div>

	<!---Modal for delete request--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-delete-request">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body bg-danger">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-exclamation-triangle"></span></h5>
						<h5 class="text-center text-white mt-2">Are you sure you want to cancel this request?</h5>
					</div>
					<!---Loader--->
					<div class="row my-2 justify-content-center" id="loader-delete" style="display: none;">
						<h6 class="m-0 ml-1 text-white">Please wait...</h6>
						<img src="resources/loader.jpg" alt="loader gif" height="20" width="20">
					</div>
					<div class="row mt-4 justify-content-center">
						<div class="col-4 px-1">
							<button class="btn btn-outline-light btn-sm form-control" id="no-btn" type="button" data-dismiss="modal" data-target="#modal-success-request" style="font-weight: 500;">No</button>
						</div>
						<div class="col-4 px-1">
							<button class="btn btn-light btn-sm form-control" id="confirm-cancel-request" type="button" style="font-weight: 500;">Yes</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!---Modal for success delete--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-success-delete">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-check-circle"></span></h5>
						<h5 class="text-center text-white mt-2">Request Cancelled</h5>
					</div>
					<div class="row mt-4 justify-content-center">
						<div class="col-6">
							<a class="btn btn-light btn-sm form-control" type="button" data-dismiss="modal" data-target="#modal-success-delete" style="font-weight: 500;">Close</a>
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
	<script type='text/javascript' src='js/requesthistory.js'></script>
	<script type="text/javascript" src="js/datatables.min.js"></script>
	<script type="text/javascript" src="js/responsive.dataTables.min.js"></script>
</body>
</html>