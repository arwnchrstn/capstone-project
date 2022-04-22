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
	<link rel="stylesheet" href="css/cropper.css">
	<link rel="stylesheet" href="jquery-ui-1.12.1.custom/jquery-ui.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<title>Create User Account</title>
	<style>
		@import 'css/index.css';
	</style>
</head>
<body>
	<!----Main container---->
	<div class="container-fluid" id="main-container">							
		<div class="row h-100 justify-content-center align-items-center">
			<!----Main panel---->
			<div class="col-md-10 col-11">
				<div class="card my-4 py-3" id="main-panel">
					<div class="card-body">
						<!----Form---->
						<form id="createacct-form" onsubmit="return false" enctype="multipart/form-data" novalidate>
							<!----Barangay Logo---->
							<div class="form-row mb-2 justify-content-center">
								<img src="resources/brgy-logo.png" alt="brgy-logo.png" width="55" height="55">
								<h5 class="ml-2 pt-3 text-center">Barangay Bigaa Online Certificate Request</h5>
							</div>
							<h5 class="text-success text-center mb-2">Create Account</h5>
							<!----Image holder---->
							<div class="form-row justify-content-center">
								<img src="uploaded image/default_profile.png" alt="user_picture" width="180" height="180" style="border: 3px solid rgb(69, 176, 111);" id="image-container">
							</div>
							<!----Upload Image---->
							<div class="form-row mt-1 justify-content-center">
								<input type="file" name="profile-pic" id="profile-upload" accept="image/jpg, image/jpeg, image/png" hidden>
								<label for="profile-upload"><a class="btn btn-success btn-sm" id="upload-photo-btn" type="button">Upload Picture</a></label>
								<div class="invalid-feedback text-center" id="photo-error"></div>
							</div>
							<div class="form-row justify-content-center">
								<a class="text-success text-center btn pb-1" type="button" id="clear-photo" style="font-weight: 500;">Clear Photo</a>
							</div>
							<div class="form-row mt-1">
								<div class="col-12">
									<h6 style="color: red;">Required Fields (*)</h6>
									<h5 class="mt-2 text-success">Account Information</h5>
									<hr class="mb-2" id="line-separator">	
								</div>
								<div class="col-md-4">
									<label class="m-0 ml-1" for="create-email-field"><small style="font-weight: 500">Email <span style="color: red;">*</span></small></label>
									<input class="form-control form-control-sm" type="email" name="email-createacct" id="create-email-field" placeholder="Email" maxlength="60">
									<div class="invalid-feedback" id="create-email-error"></div>
								</div>
								<div class="col-md-4 mt-2 mt-md-0">
									<label class="m-0 ml-1" for="create-pass-field"><small style="font-weight: 500">Password <span style="color: red;">*</span></small></label>
									<input class="form-control form-control-sm" type="password" name="pass-createacct" id="create-pass-field" placeholder="Password" maxlength="256">
									<div class="invalid-feedback" id="create-pass-error"></div>
								</div>
								<div class="col-md-4 mt-2 mt-md-0">
									<label class="m-0 ml-1" for="display-bplace"><small style="font-weight: 500">Confirm Password <span style="color: red;">*</span></small></label>
									<input class="form-control form-control-sm" type="password" id="create-confpass-field" placeholder="Confirm Password" maxlength="256">
									<div class="invalid-feedback" id="create-confpass-error"></div>
								</div>
							</div>
							<div class="form-row mt-2">
								<div class="col-10">
									<div class="custom-control custom-checkbox">
										<input class="custom-control-input" type="checkbox" id="show-pass-toggle">
										<label class="custom-control-label" for="show-pass-toggle"><small>Show password</small></label>
									</div>
								</div>
							</div>
							<!----Resident Info---->
							<div class="form-row mt-2">
								<div class="col-12">
									<h5 class="mt-2 text-success">Resident Information</h5>
									<hr class="mb-2" id="line-separator">	
								</div>
								<div class="col-md-3">
									<label class="m-0 ml-1" for="create-fname-field"><small style="font-weight: 500">First Name <span style="color: red;">*</span></small></label>
									<input class="form-control form-control-sm" type="text" name="fname-create" id="create-fname-field" placeholder="First Name" maxlength="50">
									<div class="invalid-feedback" id="create-fname-error"></div>
								</div>
								<div class="col-md-3">
									<label class="m-0 ml-1" for="create-mname-field"><small style="font-weight: 500">Middle Name</small></label>
									<input class="form-control form-control-sm mt-2 mt-md-0" type="text" name="mname-create" id="create-mname-field" placeholder="Middle Name" maxlength="50">
									<div class="invalid-feedback" id="create-mname-error"></div>
								</div>
								<div class="col-md-3">
									<label class="m-0 ml-1" for="create-lname-field"><small style="font-weight: 500">Last Name <span style="color: red;">*</span></small></label>
									<input class="form-control form-control-sm mt-2 mt-md-0" type="text" name="lname-create" id="create-lname-field" placeholder="Last Name" maxlength="50">
									<div class="invalid-feedback" id="create-lname-error"></div>
								</div>
								<div class="col-md-3">
									<label class="m-0 ml-1" for="create-suffix-field"><small style="font-weight: 500">Suffix <span style="color: red;">*</span></small></label>
									<select class="custom-select custom-select-sm mt-2 mt-md-0" name="suffix-create" id="create-suffix-field">
										<option value="N/A" selected>N/A (Not Applicable)</option>
										<option value="Jr">Jr</option>
										<option value="Sr">Sr</option>
										<option value="III">III</option>
										<option value="IV">IV</option>
										<option value="V">V</option>
										<option value="VI">VI</option>
										<option value="VII">VII</option>
										<option value="VIII">VIII</option>
										<option value="IX">IX</option>
									</select>
									<div class="invalid-feedback" id="create-suffix-error"></div>
								</div>
							</div>
							<!----Resident Info 2nd row---->
							<div class="form-row mt-2">
								<div class="col-md-3">
									<label class="m-0 ml-1" for="create-bdate-field"><small style="font-weight: 500">Birthdate <span style="color: red;">*</span></small></label>
									<input class="form-control form-control-sm" type="text" id="create-bdate-field" name="bdate-create" placeholder="mm/dd/yyyy" style="background-color: white;" maxlength="10">
									<div class="invalid-feedback" id="create-bdate-error"></div>
								</div>
								<div class="col-md-3">
									<label class="m-0 ml-1" for="create-bplace-field"><small style="font-weight: 500">Birthplace <span style="color: red;">*</span></small></label>
									<input class="form-control form-control-sm mt-2 mt-md-0" type="text" name="bplace-create" id="create-bplace-field" placeholder="Birthplace" maxlength="60">
									<div class="invalid-feedback" id="create-bplace-error"></div>
								</div>
								<div class="col-md-3">
									<label class="m-0 ml-1" for="gender-create"><small style="font-weight: 500">Gender <span style="color: red;">*</span></small></label>
									<select class="custom-select custom-select-sm mt-2 mt-md-0" name="gender-create" id="gender-select">
										<option value="Male" selected>Male</option>
										<option value="Female">Female</option>
									</select>
									<div class="invalid-feedback" id="gender-error"></div>
								</div>
								<div class="col-md-3">
									<label class="m-0 ml-1" for="civilstat-create"><small style="font-weight: 500">Civil Status <span style="color: red;">*</span></small></label>
									<select class="custom-select custom-select-sm mt-2 mt-md-0" name="civilstat-create" id="civilstat-select">
										<option value="Single" selected>Single</option>
										<option value="Married">Married</option>
										<option value="Separated">Separated</option>
										<option value="Divorced">Divorced</option>
										<option value="Widowed">Widowed</option>
									</select>
									<div class="invalid-feedback" id="civilstat-error"></div>
								</div>
							</div>
							<!----Resident Info 3rd row---->
							<div class="form-row mt-2">
								<div class="col-md-4">
									<label class="m-0 ml-1" for="create-address-field"><small style="font-weight: 500">Address <span style="color: red;">*</span></small></label>
									<input class="form-control form-control-sm" type="text" name="address-create" id="create-address-field" placeholder="Address" maxlength="256">
									<div class="invalid-feedback" id="create-address-error"></div>
								</div>
								<div class="col-md-4">
									<label class="m-0 ml-1" for="year-of-stay-field"><small style="font-weight: 500">Month/Year of stay <span style="color: red;">*</span></small></label>
									<div class="row">
										<div class="col-6 pr-1">
											<select class="custom-select custom-select-sm mt-2 mt-md-0" name="yearofstay-create-select" id="year-of-stay-select">
												<option value="1" selected>January</option>
												<option value="2">February</option>
												<option value="3">March</option>
												<option value="4">April</option>
												<option value="5">May</option>
												<option value="6" >June</option>
												<option value="7">July</option>
												<option value="8">August</option>
												<option value="9">September</option>
												<option value="0">October</option>
												<option value="11" >November</option>
												<option value="12">December</option>
											</select>
										</div>
										<div class="col-6 pl-1 pt-2 pt-md-0">
											<input type="text" class="form-control form-control-sm" name="yearofstay-create" id="year-of-stay-field" placeholder="Year" maxlength="4">
											<div class="invalid-feedback" id="year-of-stay-error"></div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<label class="m-0 ml-1" for="create-contactno-field"><small style="font-weight: 500">Mobile Number <span style="color: red;">*</span></small></label>
									<input type="text" class="form-control form-control-sm" name="contactno-create" id="create-contactno-field" placeholder="(ex. 09xxxxxxxxx)" maxlength="11">
									<div class="invalid-feedback" id="create-contactno-error"></div>
								</div>
							</div>
							<!---upload voters ID--->
							<div class="form-row mt-1">
								<div class="col-12">
									<h5 class="mt-2 text-success">Upload a picture of Voters ID or Voters Certificate <span style="color: red;">*</span></h5>
									<hr id="line-separator">	
								</div>
							</div>
							<p class="form-text text-muted mb-3 ml-3" style="font-weight: 500;">*Voters ID/Certification will help us to verify if you are a registered voter and eligible to request certificates</p>
							<p class="form-text text-muted mb-3 ml-3" style="font-weight: 500;">*If minor or you don't own voters ID or certificate yet, upload guardian or parent's voters ID</p>
							<!----Image holder voters---->
							<div class="form-row justify-content-center">
								<img src="resources/upload_id_icon.png" alt="voters_id_picture" style="border: 3px solid rgb(69, 176, 111); max-width: 70%; max-height: 500px;" id="image-container-voters">
							</div>
							<!----Upload Image voters---->
							<div class="form-row mt-1 justify-content-center">
								<input type="file" name="voters-id" id="voters-upload" accept="image/jpg, image/jpeg, image/png" hidden>
								<label for="voters-upload"><a class="btn btn-success btn-sm" type="button">Upload Picture</a></label>
								<div class="invalid-feedback text-center" id="voters-photo-error"></div>
							</div>
							<div class="form-row justify-content-center">
								<a class="text-success text-center btn" type="button" id="clear-photo-voters" style="font-weight: 500;">Clear Photo</a>
							</div>
							<!--Certify-->
							<div class="form-row my-2">
								<div class="col-12">
									<div class="custom-control custom-checkbox">
										<input class="custom-control-input" type="checkbox" id="certify-checkbox">
										<label class="custom-control-label" for="certify-checkbox"><strong>I hereby certify that the information provided above is true and correct.</strong></label>
										<div class="invalid-feedback text-center" id="certify-error"></div>
									</div>
								</div>
							</div>
							<!--DPA-->
							<div class="form-row my-2">
								<div class="col-12">
									<div class="custom-control custom-checkbox">
										<input class="custom-control-input" type="checkbox" id="terms-checkbox">
										<label class="custom-control-label" for="terms-checkbox"><strong>I agree to the terms and condition</strong> that my information will be used to complete this project and must comply to the Data Privacy Act of 2012. With this help, it can be used to request needed certificates that will come from our barangay. The information you provided above will be used only inside the Barangay Bigaa and will be used to request specific certificate.</label>
										<div class="invalid-feedback text-center" id="terms-error"></div>
									</div>
								</div>
							</div>
							<div class="form-row mt-3">
								<div class="col-md-3 mx-auto">
									<button class="form-control form-control-sm btn btn-success" type="button" id="proceed-create-btn">Proceed</button>
									<div class="invalid-feedback text-center" id="error-proceed">Please double check the information above</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!----Modal for info confirmation---->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-confirm-info">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color: rgb(69, 176, 111);">
					<h6 class="text-white m-0">Review your information</h6>
					<a class="close text-white" type="button" data-dismiss="modal" data-target="#modal-confirm-info"><span>&times;</span></a>
				</div>
				<div class="body">
					<div class="row mt-4 justify-content-center">
						<h5 class="text-center text-success">User Photo</h5>
					</div>
					<div class="row mt-1 justify-content-center">
						<img src="" alt="user-photo-confirm" id="user-photo-confirm" width="150" height="150" style="border: 3px solid rgb(69, 176, 111);">
					</div>
					<div class="row mt-4 justify-content-center">
						<h5 class="text-center text-success">Resident Information</h5>
					</div>
					<div class="row mt-1 mx-3">
						<div class="col-12">
							<span><b>Full Name</b>&nbsp;: <span id="full-name">&nbsp;</span>
						</div>
					</div>
					<div class="row mt-3 mx-3">
						<div class="col-12">
							<span><b>Address</b>&nbsp;: <span id="address">&nbsp;</span>
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
							<span><b>Year of Stay</b>&nbsp;: <span id="year-of-stay">&nbsp;</span></span>
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
						<img src="" alt="user-voters-confirm" id="user-voters-confirm" style="border: 3px solid rgb(69, 176, 111); max-width: 90%; max-height: 500px;">
					</div>
					<div class="row mt-4 mb-3 mx-3 justify-content-center">
						<div class="col-5 pr-2">
							<button class="btn btn-danger btn-sm form-control" type="button" id="go-back-btn" data-dismiss="modal" data-target="#modal-confirm-info">Go Back</button>
						</div>
						<div class="col-5 pl-0">
							<button class="btn btn-success btn-sm form-control" type="button" id="register-btn">Register</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--Modal for processing request-->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-loading-process">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-white mt-2">Please wait... <span><img src="resources/loader.jpg" alt="loader gif" height="23" width="23"></span></h5>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!----Modal for crop image---->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-crop-img">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color: rgb(69, 176, 111);">
					<h5 class="text-white m-0">Crop Image</h5>
					<a class="text-white close" type="button" data-dismiss="modal"><span>&times;</span></a>
				</div>
				<div class="modal-body">
					<!---Image container--->
					<div class="row justify-content-center mx-2">
						<img src="" id="image-crop-container" alt="image-profile-crop" width="100%">
					</div>
					<!---Buttons--->
					<div class="row justify-content-center mt-3">
						<div class="col-5">
							<button class="btn btn-danger btn-sm form-control" type="button" id="cancel-crop" data-dismiss="modal" data-target="modal-crop-img">Cancel</button>
						</div>
						<div class="col-5">
							<button class="btn btn-success btn-sm form-control" type="button" id="crop-image-display">Crop</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!---Modal for success create account--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-success-create">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-envelope"></span></h5>
						<h6 class="text-center text-white mt-3">Please confirm your email</h6>
						<p class="text-center text-white mt-2">Please check your email for confirmation. If you don't receive the email, please check it on your spam folder, Thank You!</p>
					</div>
					<div class="row justify-content-center mt-4">
						<div class="col-5">
							<button class="btn btn-light form-control form-control-sm" data-dismiss="modal" data-target="#modal-success-create" style="font-weight: 500;">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type='text/javascript' src='js/jquery-3.6.0.min.js'></script>
	<script type='text/javascript' src='js/popper.min.js'></script>
	<script type='text/javascript' src='js/cropper.js'></script>
	<script type='text/javascript' src='js/bootstrap.min.js'></script>
	<script type='text/javascript' src='fontawesome/js/all.js'></script>
	<script type='text/javascript' src='js/createaccount.js'></script>
	<script type="text/javascript" src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>
</body>
</html>