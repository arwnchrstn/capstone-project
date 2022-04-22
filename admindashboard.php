<?php
	try{
		if(!@include 'includes/load_admin_name.php'){
			throw new Exception('File error: Unable to load configuration file');
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
	<link rel="stylesheet" href="css/admindashboard.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<title>Admin Dashboard</title>
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
			<!---First Row--->
			<h4 class="ml-3 mt-3">Requests</h4>
			<div class="row justify-content-center mx-1">
				<div class="col-4 pr-0 pl-5">
					<div class="card" style="position: relative;">
						<img src="resources/pending request.png" alt="pending-icon" height="50" width="50" style="position: absolute; right: 6%; top: 9%;">
						<div class="card-header" style="border-left: 4px solid #28a745;">
							<h4 id="pending-count">--</h4>
							<p class="text-success" style="font-weight: 500;">Pending Request</p>
						</div>
						<div class="card-footer py-1">
							<p class="m-0 text-center text-white"><a class="btn text-success py-0" role="button" onclick="window.location.href= 'pendingrequest'" style="font-weight: 500;">More Info <span class="fas fa-arrow-circle-right"></span></a></p>
						</div>
					</div>
				</div>
				<div class="col-4 px-3">
					<div class="card" style="position: relative;">
						<img src="resources/request in process.png" alt="request-in-process-icon" height="55" width="55" style="position: absolute; right: 5%; top: 9%;">
						<div class="card-header" style="border-left: 4px solid #28a745;">
							<h4 id="processing-count">--</h4>
							<p class="text-success" style="font-weight: 500;">Request in Process</p>
						</div>
						<div class="card-footer py-1">
							<p class="m-0 text-center text-success"><a class="btn text-success py-0" role="button" onclick="window.location.href= 'processingrequest'" style="font-weight: 500;">More Info <span class="fas fa-arrow-circle-right"></span></a></p>
						</div>
					</div>
				</div>
				<div class="col-4 pl-0 pr-5">
					<div class="card" style="position: relative;">
						<img src="resources/for pickup request.png" alt="for-pickup-request-icon" height="50" width="50" style="position: absolute; right: 6%; top: 10%;">
						<div class="card-header" style="border-left: 4px solid #28a745;">
							<h4 id="pickup-count">--</h4>
							<p class="text-success" style="font-weight: 500;">For Pickup Request</p>
						</div>
						<div class="card-footer py-1">
							<p class="m-0 text-center text-success"><a class="btn text-success py-0" role="button" onclick="window.location.href = 'requestforpickup'" style="font-weight: 500;">More Info <span class="fas fa-arrow-circle-right"></span></a></p>
						</div>
					</div>
				</div>
			</div>
			<!--Third Row-->
			<h4 class="ml-3 mt-4">Registered Residents</h4>
			<div class="row" style="margin-bottom: 80px;">
				<div class="col-4 pr-0 pl-5">
					<div class="card" style="position: relative;">
						<img src="resources/notverified.png" alt="non-verified-icon" height="50" width="50" style="position: absolute; right: 5%; top: 9%;">
						<div class="card-header" style="border-left: 4px solid #28a745;">
							<h4 id="non-verified-user">--</h4>
							<p class="text-success" style="font-weight: 500;">Non-verified Residents</p>
						</div>
						<div class="card-footer py-1">
							<p class="m-0 text-center text-success"><a class="btn text-success py-0" role="button" style="font-weight: 500;" onclick="window.location.href = 'non-verified-users'">More Info <span class="fas fa-arrow-circle-right"></span></a></p>
						</div>
					</div>
				</div>
				<div class="col-4 px-3">
					<div class="card" style="position: relative;">
						<img src="resources/verified.png" alt="pending-icon" height="50" width="50" style="position: absolute; right: 6%; top: 9%;">
						<div class="card-header" style="border-left: 4px solid #28a745;">
							<h4 id="verified-user">--</h4>
							<p class="text-success" style="font-weight: 500;">Verified Residents</p>
						</div>
						<div class="card-footer py-1">
							<p class="m-0 text-center text-success"><a class="btn text-success py-0" role="button" style="font-weight: 500;" onclick="window.location.href = 'verified-users'">More Info <span class="fas fa-arrow-circle-right"></span></a></p>
						</div>
					</div>
				</div>
				<div class="col-4 pl-0 pr-5">
					<div class="card" style="position: relative;">
						<img src="resources/TRU.png" alt="total-registered-user-icon" height="50" width="50" style="position: absolute; right: 6%; top: 9%;">
						<div class="card-header" style="border-left: 4px solid #28a745;">
							<h4 class="pb-2" id="total-registered-user">--</h4>
							<p class="text-success pb-4" style="font-weight: 500;">Total Registered Residents</p>
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
	<script type='text/javascript' src="js/admindashboard.js"></script>
</body>
</html>