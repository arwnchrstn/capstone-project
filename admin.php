<?php
	//start sessions
	try{
		if(!@include 'includes/db_connection.php'){
			throw new Exception('File Error: Unable to load configuration file');
		}
		else{
			if(isset($_SESSION['ADMIN_ID'])){
				header('location: admindashboard');
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
	<title>Admin Login</title>
	<style>
		@import 'css/index.css';

		#main-container{
			min-width: 850px;
			max-width: 2000px;
		}

		.show-screen-error{
			height: 100vh;
			display: none;
			overflow: hidden;
		}

		@media screen and (max-width: 850px){
			.wrapper{
				display: none;
			}
			.show-screen-error{
				display: block;
			}
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
	<!----Main container---->
	<div class="container-fluid" id="main-container">							
		<div class="row h-100 justify-content-center align-items-center">
			<!----Main panel---->
			<div class="col-md-5">
				<div class="card my-5" id="main-panel">
					<div class="card-body">
						<div class="row">
							<!----Login panel---->
							<div class="col-10 offset-1 my-4">
								<!----Logo---->
								<div class="row justify-content-center">
									<img class="mb-3" src="resources/brgy-logo.png" alt="brgy-logo.png" style="height: 20%; width: 20%;">
								</div>
								<h5 class="text-center mb-3">Barangay Bigaa Online Certificate Request</h5>
								<h4 class="text-center text-success">Admin Login</h4>
								<!----Error Alert---->
								<div class="alert alert-danger col-10 offset-1 py-1 collapse" role="alert" id="alert-error-login">
									<small id="alert-error">Username or password does not match our records</small>
								</div>
								<!----Admin Login Form---->
								<form id="admin-login-form" onsubmit="return false" novalidate>
									<div class="form-row justify-content-center">
										<div class="col-10">
											<input type="email" class="form-control form-control-sm" name="admin-username" id="admin-user-field" placeholder="Username">
											<div class="invalid-feedback" id="admin-user-error"></div>
										</div>
									</div>
									<div class="form-row justify-content-center mt-2">
										<div class="col-10">
											<input type="password" class="form-control form-control-sm" name="admin-password" id="admin-pass-field" placeholder="Password">
											<div class="invalid-feedback" id="admin-pass-error"></div>
										</div>
									</div>
									<div class="form-row mt-2">
										<div class="col-10 offset-1">
											<div class="custom-control custom-checkbox">
												<input class="custom-control-input" type="checkbox" id="show-pass-toggle">
	  											<label class="custom-control-label" for="show-pass-toggle"><small>Show password</small></label>
											</div>
										</div>
									</div>
									<!---Loader--->
									<div class="form-row mt-2" id="loader" style="display: none;">
										<h6 class="m-0 mr-1 text-success ml-auto">Please wait...</h6>
										<img class="mr-auto" src="resources/loader.jpg" alt="loader gif" height="20" width="20">
									</div>
									<div class="form-row justify-content-center mt-3">
										<div class="col-10">
											<button class="btn btn-success form-control form-control-sm" type="submit" id="admin-login-btn">Login</button>
										</div>
									</div>
								</form>
							</div>
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
	<script type='text/javascript' src="js/adminlogin.js"></script>
</body>
</html>