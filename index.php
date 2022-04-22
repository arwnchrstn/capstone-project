<?php
	//start sessions
	try{
		if(!@include 'includes/db_connection.php'){
			throw new Exception('File Error: Unable to load configuration file');
		}
		else{
			if(isset($_SESSION['ID'])){
				header('location:dashboard');
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
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="jquery-ui-1.12.1.custom/jquery-ui.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<title>Barangay Bigaa Certificate Request</title>
</head>
<body>
	<!----Main container---->
	<div class="container-fluid" id="main-container">							
		<div class="row h-100 justify-content-center align-items-center">
			<!----Main panel---->
			<div class="col-11 col-md-10">
				<div class="card my-5" id="main-panel">
					<div class="card-body">
						<div class="row">
							<!----Desc panel---->
							<div class="col-md-7 pl-md-4 pr-0 order-md-1 order-3 pl-0" id="desc-panel">
								<div class="row h-100 align-items-center justify-content-center">
									<div class="col-10" id="accordion-index">
										<h4 class="pt-2 text-center" style="font-weight: 500;">About the system</h4>
										<div>
											<p>This web-based system will allow the residents of Barangay Bigaa to request certificates online, the certificates that can be requested are limited to:</p>
											<ul class="pl-3">
												<li>Certificate of Residency</li>
												<li>Certificate of Indigency</li>
												<li>Barangay Clearance</li>
											</ul>
											<p class="m-0">Residents who wants to access the system should register before requesting a document</p>
										</div>
										<h4 class="pt-2 text-center">Mission of the Barangay</h4>
										<div>
											<p class="m-0">Maipagkaloob at maiangat ang pangangalaga ng mamamayan ng Barangay Bigaa na matapat at responsableng paglilingkod, makatulong sa pagsuporta sa mga nagmamantena ng gamot sa mga Senior Citizen at may kapansanan, mabigyan ng regalong pangbirthday ang mga Senior Citizen at mapalakas ang antas ng karunungan sa pamamagitan ng paghikayat sa mga out-of-school youth.</p>
										</div>
										<h4 class="pt-2 text-center">Vision of the Barangay</h4>
										<div>
											<p class="m-0">Ang Barangay Bigaa ay maging masigla, matahimik, may pagkakaisa, at pinamumunuan ng isang Punong Barangay na may pananalig sa Diyos, makatao, at may kakayahang makatulong sa pangangailangan ng mga mamamayan.</p>
										</div>
										<h4 class="pt-2 text-center">Contact Us</h4>
										<div>
											<p><span class="text-success fas fa-envelope"></span> Email: bigaacabuyaocity@gmail.com</p>
											<p class="m-0"><span class="text-success fas fa-phone-alt"></span> Hotline: (049) 304-3268</p>
										</div>
									</div>
								</div>
							</div>
							<!----Line separator---->
							<div class="col-10 d-md-none d-block order-md-0 order-2 offset-1">
								<hr id="line-separator">	
							</div>
							<!----Login panel---->
							<div class="col-md-5 my-4 pr-md-4 pl-0 order-md-2 order-1 pr-0" id="login-panel">
								<div class="row h-100 align-items-center justify-content-center">
									<div class="col-12">
										<!----Logo---->
										<div class="row justify-content-center">
											<img class="mb-3" src="resources/brgy-logo.png" alt="brgy-logo.png" style="height: 25%; width: 25%;">
										</div>
										<h5 class="text-center mb-3">Barangay Bigaa Online Certificate Request</h5>
										<!----Error Alert---->
										<div class="alert alert-danger col-10 offset-1 py-1 collapse" role="alert" id="alert-error-login">
											<small id="alert-error">Email or password does not match our records</small>
										</div>
										<!----Form---->
										<form id="user-login-form" onsubmit="return false" novalidate>
											<div class="form-row justify-content-center">
												<div class="col-10">
													<input type="email" class="form-control form-control-sm" name="email-user" id="emailuser-login-field" placeholder="Email">
													<div class="invalid-feedback" id="user-email-error"></div>
												</div>
											</div>
											<div class="form-row mt-2 justify-content-center">
												<div class="col-10">
													<input type="password" class="form-control form-control-sm" name="password-user" id="passuser-login-field" placeholder="Password">
													<div class="invalid-feedback" id="user-pass-error"></div>
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
											<div class="form-row mt-2 justify-content-center">
												<div class="col-10">
													<button class="btn btn-success form-control form-control-sm" id="user-login-btn" type="submit">Login</button>
												</div>
											</div>
											<div class="row justify-content-center">
												<div class="col-md-5 mt-2">
													<div class="row">
														<a class="btn text-success mx-auto p-0" type="button" id="forgot-pass-user" data-toggle="modal" data-target="#modal-forgot-pass">Forgot password?</a>
													</div>
													<div class="row">
														<a class="btn text-success mx-auto p-0 mt-1" type="button" id="create-user-acct">Register Now!</a>
													</div>
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
		</div>
	</div>

	<!---Modal for forgot password--->
	<div class="modal show modal fade" role="dialog" tabindex="-1" id="modal-forgot-pass" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color: rgb(69, 176, 111);">
					<h5 class="text-white m-0">Forgot Password</h5>
					<button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<h6>Please enter your e-mail address</h6>	
						</div>
					</div>
					<form id="forgot-pass-form" onsubmit="return false" novalidate>
						<div class="form-row mt-1">
							<div class="col-12">
								<input type="email" class="form-control" name="email-reset-pass" id="email-resetpass-field" placeholder="E-mail">
								<div class="invalid-feedback" id="resetpass-email-error"></div>
							</div>
						</div>
						<!---Loader--->
						<div class="form-row mt-2" id="loader-reset" style="display: none;">
							<h6 class="m-0 ml-2 text-success">Please wait...</h6>
							<img class="mr-auto" src="resources/loader.jpg" alt="loader gif" height="18" width="18">
						</div>
						<div class="form-row mt-2">
							<button id="reset-btn" class="btn btn-success btn-sm ml-auto mr-1" type="submit">Reset Password</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!---Modal for success reset password--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-success-reset">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-envelope"></span></h5>
						<h6 class="text-center text-white mt-3">We have sent you an email to reset your password</h6>
						<p class="text-center text-white mt-2">Please check your email for confirmation. If you don't receive the email, please check it on your spam folder, Thank You!</p>
					</div>
					<div class="row justify-content-center mt-4">
						<div class="col-5">
							<button class="btn btn-light form-control form-control-sm" data-dismiss="modal" data-target="#modal-success-reset" style="font-weight: 500;">Close</button>
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
	<script type="text/javascript" src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>
	<script type='text/javascript' src="js/index.js"></script>
</body>
</html>