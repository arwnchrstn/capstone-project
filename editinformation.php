<?php
	try{
		if(!@include 'includes/load_user_data_dashboard.php'){
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
	<link rel="stylesheet" href="css/cropper.css">
	<link rel="stylesheet" href="jquery-ui-1.12.1.custom/jquery-ui.css">
	<link rel="icon" href="resources/brgy-logo.png" type="image/x-icon"/>
	<title>Edit Information</title>
	<style>
		@import 'css/dashboard.css';

		#back2dash{
			font-weight: 500; 
			font-size: 18px;
		}

		@media screen and (max-width:  300px){
			#back2dash{
				font-size: 15px;
			}
		}
	</style>
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
						<a href="requesthistory" id="req-history-user" class="nav-link" style="font-size: 15px; font-weight: 500;">Request History</a>
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
		<div class="row justify-content-center" style="margin-top: 80px;">
			<div class="col-md-11 col-12">
				<a class="btn text-success pl-2 pb-2" id="back2dash" type="button" onclick="window.location.href='dashboard'"><span class="fas fa-arrow-left text success"></span> Back to Dashboard</a>
				<div class="card mb-5">
					<div class="card-header bg-white">
						<h5 class="text-success m-0 d-inline-block">Edit Information</h5>
					</div>
					<div class="card-body">
						<div class="row justify-content-center mt-2">
							<img class="mx-3" src="<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); echo 'uploaded image/profile pictures/'.$array['picture_name']; unset($res_info); ?>" width="150" height="150" alt="user-photo" style="border: 3px solid rgb(69, 176, 111); max-width: 100%;" id="image-edit-holder">
						</div>
						<div class="row justify-content-center">
							<input type="file" id="profile-upload-edit" accept="image/jpg, image/jpeg, image/png" hidden>
							<label for="profile-upload-edit"><a class="btn btn-success btn-sm mt-2" type="button">Update Picture&nbsp;<span class="fas fa-camera"></span></a></label>
						</div>
						<div class="row mt-4">
							<div class="col-md-12">
								<!---First row--->
								<div class="row px-3">
									<div class="col-md-3 px-1">
										<label class="m-0 ml-1" for="edit-fname-field"><small style="font-weight: 500">First Name</small></label>
										<input class="form-control form-control-sm" type="text" name="fname-edit" id="edit-fname-field" placeholder="(ex. Juan)" value="<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); echo $array['first_name']; unset($res_info); ?>" style="text-transform: uppercase;" maxlength="60">
										<div class="invalid-feedback" id="edit-fname-error"></div>
									</div>
									<div class="col-md-3 px-1">
										<label class="m-0 ml-1" for="edit-mname-field"><small style="font-weight: 500">Middle Name</small></label>
										<input class="form-control form-control-sm mt-2 mt-md-0" type="text" name="mname-edit" id="edit-mname-field" placeholder="(ex. Gregorio)" value="<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); echo $array['middle_name']; unset($res_info); ?>" style="text-transform: uppercase;" maxlength="60">
										<div class="invalid-feedback" id="edit-mname-error"></div>
									</div>
									<div class="col-md-3 px-1">
										<label class="m-0 ml-1" for="edit-lname-field"><small style="font-weight: 500">Last Name</small></label>
										<input class="form-control form-control-sm mt-2 mt-md-0" type="text" name="lname-edit" id="edit-lname-field" placeholder="(ex. Dela Cruz)" value="<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); echo $array['last_name']; unset($res_info); ?>" style="text-transform: uppercase;" maxlength="60">
										<div class="invalid-feedback" id="edit-lname-error"></div>
									</div>
									<div class="col-md-3 px-1">
										<label class="m-0 ml-1" for="edit-suffix-field"><small style="font-weight: 500">Suffix</small></label>
										<select class="custom-select custom-select-sm mt-2 mt-md-0" name="suffix-edit" id="edit-suffix-select">
											<option value="N/A" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'N/A') { echo 'selected="selected"'; } unset($res_info); ?> >N/A (Not Applicable)</option>
											<option value="Jr" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'JR') { echo 'selected="selected"'; } unset($res_info); ?> >Jr</option>
											<option value="Sr" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'SR') { echo 'selected="selected"'; } unset($res_info); ?> >Sr</option>
											<option value="III" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'III') { echo 'selected="selected"'; } unset($res_info); ?> >III</option>
											<option value="IV" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'IV') { echo 'selected="selected"'; } unset($res_info); ?> >IV</option>
											<option value="V" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'V') { echo 'selected="selected"'; } unset($res_info); ?> >V</option>
											<option value="VI" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'VI') { echo 'selected="selected"'; } unset($res_info); ?> >VI</option>
											<option value="VII" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'VII') { echo 'selected="selected"'; } unset($res_info); ?> >VII</option>
											<option value="VIII" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'VIII') { echo 'selected="selected"'; } unset($res_info); ?> >VIII</option>
											<option value="IX" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['suffix'] == 'IX') { echo 'selected="selected"'; } unset($res_info); ?> >IX</option>
										</select>
										<div class="invalid-feedback" id="edit-suffix-error"></div>
									</div>
								</div>
								<!---Second Row--->
								<div class="row mt-2 px-3">
									<div class="col-md-6 px-1">
										<label class="m-0 ml-1" for="edit-email-field"><small style="font-weight: 500">Email Address</small></label>
										<input class="form-control form-control-sm" type="email" id="edit-email-field" name="email-edit" placeholder="(ex.sample@email.com)" value="<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); echo $array['email']; unset($res_info); ?>" maxlength="60" disabled>
										<div class="invalid-feedback" id="edit-email-error"></div>
									</div>
									<div class="col-md-6 px-1">
										<label class="m-0 ml-1" for="edit-contactno-field"><small style="font-weight: 500">Mobile Number</small></label>
										<input class="form-control form-control-sm mt-2 mt-md-0" type="text" name="contactno-edit" id="edit-contactno-field" placeholder="(ex. 09xxxxxxxxx)" value="<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); echo $array['mobile_number']; unset($res_info); ?>"
										maxlength="11">
										<div class="invalid-feedback" id="edit-contactno-error"></div>
									</div>
								</div>
								<!---Third Row--->
								<div class="row mt-2 px-3">
									<div class="col-md-4 px-1">
										<label class="m-0 ml-1" for="edit-bdate-field"><small style="font-weight: 500">Birthdate</small></label>
										<input class="form-control form-control-sm" type="text" id="edit-bdate-field" name="bdate-edit" value="<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); echo $array['birthdate']; unset($res_info); ?>" placeholder="yyyy-mm-dd" disabled>
										<div class="invalid-feedback" id="edit-bdate-error"></div>
									</div>
									<div class="col-md-4 px-1">
										<label class="m-0 ml-1" for="edit-bplace-field"><small style="font-weight: 500">Birthplace</small></label>
										<input class="form-control form-control-sm mt-2 mt-md-0" type="text" name="bplace-edit" id="edit-bplace-field" placeholder="(ex. Calamba City, Laguna)" value="<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); echo $array['birthplace']; unset($res_info); ?>" style="text-transform: uppercase;" maxlength="60">
										<div class="invalid-feedback" id="edit-bplace-error"></div>
									</div>
									<div class="col-md-4 px-1">
										<label class="m-0 ml-1" for="edit-gender-field"><small style="font-weight: 500">Gender</small></label>
										<select class="custom-select custom-select-sm mt-2 mt-md-0" name="gender-edit" id="edit-gender-select">
											<option value="Male" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['gender'] == 'MALE') { echo 'selected="selected"'; } unset($res_info); ?> >Male</option>
											<option value="Female"<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['gender'] == 'FEMALE') { echo 'selected="selected"'; } unset($res_info); ?> >Female</option>
										</select>
										<div class="invalid-feedback" id="edit-gender-error"></div>
									</div>
								</div>
								<!---Fourth Row--->
								<div class="row mt-2 px-3">
									<div class="col-md-12 px-1">
										<label class="m-0 ml-1" for="edit-address-field"><small style="font-weight: 500">Address</small></label>
										<input class="form-control form-control-sm" type="test" id="edit-address-field" name="address-edit" placeholder="(ex. 12 St. Sampaguita Village)" value="<?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); echo $array['address']; unset($res_info); ?>" style="text-transform: uppercase;" maxlength="256">
										<div class="invalid-feedback" id="edit-address-error"></div>
									</div>
								</div>
								<!---Fifth Row--->
								<div class="row mt-2 px-3 justify-content-center">
									<div class="col-md-4 px-1">
										<label class="m-0 ml-1" for="edit-civilstat-select"><small style="font-weight: 500">Civil Status</small></label>
										<select class="custom-select custom-select-sm mt-2 mt-md-0" name="civilstat-create" id="edit-civilstat-select">
											<option value="Single" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['civil_status'] == 'SINGLE') { echo 'selected="selected"'; } unset($res_info); ?> >Single</option>
											<option value="Married" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['civil_status'] == 'MARRIED') { echo 'selected="selected"'; } unset($res_info); ?> >Married</option>
											<option value="Separated" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['civil_status'] == 'SEPARATED') { echo 'selected="selected"'; } unset($res_info); ?> >Separated</option>
											<option value="Divorced" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['civil_status'] == 'DIVORCED') { echo 'selected="selected"'; } unset($res_info); ?> >Divorced</option>
											<option value="Widowed" <?php $res_info = new Functions(); $array = $res_info->getResidentData($_SESSION['ID']); if($array['civil_status'] == 'WIDOWED') { echo 'selected="selected"'; } unset($res_info); ?> >Widowed</option>
										</select>
										<div class="invalid-feedback" id="edit-civilstat-error"></div>
									</div>
									<div class="col-md-4 px-1">
										<label class="m-0 ml-1" for="year-of-stay-field"><small style="font-weight: 500">Year of Stay</small></label>
										<?php 
											$res_info = new Functions(); 
											$array = explode('-', $res_info->getResidentData($_SESSION['ID'])['year_of_stay']);
											$yearofstay = '';
											switch($array[1]){
												case 1: $yearofstay = "January $array[0]";
												break;
												case 2: $yearofstay = "February $array[0]";
												break;
												case 3: $yearofstay = "March $array[0]";
												break;
												case 4: $yearofstay = "April $array[0]";
												break;
												case 5: $yearofstay = "May $array[0]";
												break;
												case 6: $yearofstay = "June $array[0]";
												break;
												case 7: $yearofstay = "July $array[0]";
												break;
												case 8: $yearofstay = "August $array[0]";
												break;
												case 9: $yearofstay = "September $array[0]";
												break;
												case 10: $yearofstay = "October $array[0]";
												break;
												case 11: $yearofstay = "November $array[0]";
												break;
												case 12: $yearofstay = "December $array[0]";
												break;
											}
											echo '<input class="form-control form-control-sm mt-2 mt-md-0" type="text" name="year-of-stay-edit" id="year-of-stay-field" value="'.$yearofstay.'" disabled>';
											unset($res_info); 
										?>
									</div>
								</div>
								<!---Sixth Row--->
								<div class="form-row mt-5 px-3">
									<div class="col-md-3 mx-auto">
										<button class="form-control form-control-sm btn btn-success" type="button" id="save-info-btn">Save Information</button>
										<div class="invalid-feedback text-center" id="error-save-edit">Please double check the information above</div>
									</div>
								</div>
								<!---Loader--->
								<div class="form-row mt-2" id="loader" style="display: none;">
									<h5 class="m-0 mr-1 text-success ml-auto">Please wait...</h5>
									<img class="mr-auto" src="resources/loader.jpg" alt="loader gif" height="25" width="25">
								</div>
							</div>
						</div>
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

	<!---Modal for success edit information--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-success-editinfo">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-white text-center m-0" style="font-size: 100px;"><span class="fas fa-check-circle"></span></h5>
						<h5 class="text-center text-white mt-2">Information Updated Successfully</h5>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!---Modal for confirm save info--->
	<div class="modal show modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="modal-editinfo-confirm">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="background-color: rgb(69, 176, 111);">
					<!---Image container--->
					<div class="icon-box">
						<h5 class="text-center text-white mt-2">Are you sure you want to save this information?</h5>
					</div>
					<!---Loader--->
					<div class="row my-2 justify-content-center" id="loader-edit" style="display: none;">
						<h6 class="m-0 ml-1 text-white">Please wait...</h6>
						<img src="resources/loader.jpg" alt="loader gif" height="20" width="20">
					</div>
					<div class="row mt-4 justify-content-center">
						<div class="col-4 px-1">
							<button class="btn btn-outline-light btn-sm form-control" id="no-btn" type="button" data-dismiss="modal" data-target="#modal-success-request" style="font-weight: 500;">No</button>
						</div>
						<div class="col-4 px-1">
							<button class="btn btn-light btn-sm form-control" id="confirmsave-info-btn" type="button" style="font-weight: 500;">Yes</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type='text/javascript' src='js/jquery-3.6.0.min.js'></script>
	<script type='text/javascript' src='js/popper.min.js'></script>
	<script type='text/javascript' src='js/bootstrap.min.js'></script>
	<script type='text/javascript' src='js/cropper.js'></script>
	<script type='text/javascript' src='fontawesome/js/all.js'></script>
	<script type="text/javascript" src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>
	<script type='text/javascript' src='js/editinformation.js'></script>
</body>
</html>