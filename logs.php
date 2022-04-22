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
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<style>
		@import 'css/admindashboard.css';

		#dashboard-breadcrumb:hover{
			color: #28a745 !important;
			transition: ease-in 0.1s;
		}

        td{
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }
	</style>
	<title>Logs</title>
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
					<li class="breadcrumb-item text-success" aria-current="page" style="color: black; font-size: 20px; font-weight: 700;">Completed Request Logs</li>
				</ol>
			</nav>
            <!---Loader--->
			<div class="row my-2 justify-content-center" id="loader" style="display: none;">
				<h5 class="m-0 ml-1 text-success">Please wait...</h5>
				<img src="resources/loader.jpg" alt="loader gif" height="25" width="25">
			</div>
			<!--Table Row-->
			<div class="row justify-content-center mb-3">
				<div class="col-11">
					<table class="table table-bordered" cellspacing="0" width="100%" id="completed-table">
                        <thead style="background-color: rgb(69, 176, 111);">
							<tr>
								<th class="text-white text-center" style="width: 170px; vertical-align: middle;">Request Number</th>
								<th class="text-white text-center" style="width: 180px; vertical-align: middle;">Name of Requestor</th>
								<th class="text-white text-center" style="width: 150px; vertical-align: middle;">Request Status</th>
								<th class="text-white text-center" style="width: 100px; vertical-align: middle;">Type</th>
								<th class="text-white text-center" style="width: 200px; vertical-align: middle;">Purpose</th>
								<th class="text-white text-center" style="width: 140px; vertical-align: middle;">Date Completed</th>
								<th class="text-white text-center" style="width: 210px; vertical-align: middle;">Processed By</th>
							</tr>
						</thead>
						<tbody id="table-data">

						</tbody>
					</table>
				</div>
			</div>
            <nav aria-label="breadcrumb" >
				<ol class="breadcrumb mx-2 my-0" style="background: none;">
					<li class="breadcrumb-item text-success" aria-current="page" style="color: black; font-size: 20px; font-weight: 700;">Cancelled Request Logs</li>
				</ol>
			</nav>
            <!--Table Row-->
			<div class="row justify-content-center mb-3">
				<div class="col-11">
					<table class="table table-bordered" cellspacing="0" width="100%" id="cancelled-table">
                        <thead style="background-color: rgb(69, 176, 111);">
							<tr>
								<th class="text-white text-center" style="width: 170px; vertical-align: middle;">Request Number</th>
								<th class="text-white text-center" style="width: 180px; vertical-align: middle;">Name of Requestor</th>
								<th class="text-white text-center" style="width: 150px; vertical-align: middle;">Request Status</th>
								<th class="text-white text-center" style="width: 100px; vertical-align: middle;">Type</th>
								<th class="text-white text-center" style="width: 200px; vertical-align: middle;">Purpose</th>
								<th class="text-white text-center" style="width: 140px; vertical-align: middle;">Date Requested</th>
								<th class="text-white text-center" style="width: 210px; vertical-align: middle;">Processed By</th>
							</tr>
						</thead>
						<tbody id="table-data2">

						</tbody>
					</table>
				</div>
			</div>
            <nav aria-label="breadcrumb" >
				<ol class="breadcrumb mx-2 my-0" style="background: none;">
					<li class="breadcrumb-item text-success" aria-current="page" style="color: black; font-size: 20px; font-weight: 700;">Declined Request Logs</li>
				</ol>
			</nav>
            <!--Table Row-->
			<div class="row justify-content-center mb-5">
				<div class="col-11">
					<table class="table table-bordered" cellspacing="0" width="100%" id="declined-table">
                        <thead style="background-color: rgb(69, 176, 111);">
							<tr>
								<th class="text-white text-center" style="width: 170px; vertical-align: middle;">Request Number</th>
								<th class="text-white text-center" style="width: 180px; vertical-align: middle;">Name of Requestor</th>
								<th class="text-white text-center" style="width: 150px; vertical-align: middle;">Request Status</th>
								<th class="text-white text-center" style="width: 100px; vertical-align: middle;">Type</th>
								<th class="text-white text-center" style="width: 200px; vertical-align: middle;">Purpose</th>
								<th class="text-white text-center" style="width: 140px; vertical-align: middle;">Date Requested</th>
                                <th class="text-white text-center" style="width: 140px; vertical-align: middle;">Remarks</th>
								<th class="text-white text-center" style="width: 210px; vertical-align: middle;">Processed By</th>
							</tr>
						</thead>
						<tbody id="table-data3">

						</tbody>
					</table>
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
	<script type='text/javascript' src="js/logs.js"></script>
    <script type="text/javascript" src="js/datatables.min.js"></script>
	<script type="text/javascript" src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>
</body>
</html>