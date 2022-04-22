<?php 
	try{
		if(!@include '../includes/db_connection.php'){
			throw new Exception('File Error: Unable to load configuration file');
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
	<link rel="stylesheet" href="../fontawesome/css/all.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="icon" href="../resources/brgy-logo.png" type="image/x-icon"/>
	<title>Password Reset</title>
	<style>
		html{
			min-width: 280px;
			max-width: 2000px;
		}
		#wrapper{
			height: 100vh;
			background-color: rgba(69, 176, 111, 0.3);
		}
		/*Remove default show password button*/
		input[type='password']::ms-reveal, input[type='password']::ms-clear{
			display: none;
		}
		input::-ms-reveal,input::-ms-clear {
		    display: none;
		}
	</style>
</head>
<body>
	<?php 
		try{
			if(isset($_GET['key'])){
				Class Display extends DbConn{
					public function execute(){
						//check if reset key is valid
						$stmt = $this->connect()->prepare("SELECT reset_key FROM reset_password_request WHERE reset_key = ? LIMIT 1");
						$stmt->execute([$_GET['key']]);
						$result = $stmt->fetch();

						if($result){
							echo '
								<div class="container-fluid" id="wrapper">
									<div class="row justify-content-center">
										<div class="col-md-7 col-sm-9 col-11" style="margin-top: 80px;">
											<div class="card bg-white px-2" style="border-radius: 10px;">
												<div class="card-body">
													<div class="row justify-content-center mt-2">
														<img src="../resources/brgy-logo.png" alt="barangay-logo" width="100" height="100">
													</div>
													<div class="row mt-2 justify-content-center">
														<h5 class="text-center">Bigaa Online Certificate Request</h5>
													</div>
													<!--Form row-->
													<div id="form-wrapper">
														<h5 class="text-center text-success">Reset Password</h5>
														<div class="row justify-content-center">
															<div class="col-md-8 col-sm-10 col-11">
																<!----Error Alert---->
																<div class="alert alert-danger py-1 collapse" role="alert" id="alert-same-pass">
																	<small id="alert-error">New password can\'t be your old password</small>
																</div>
																<form id="reset-pass-form" onsubmit="return false" novalidate>
																	<div class="form-row">
																		<input type="password" class="form-control" id="new-pass-field" placeholder="Enter new password" maxlength="64">
																		<div class="invalid-feedback" id="error-new-pass"></div>
																	</div>
																	<div class="form-row">
																		<input type="password" class="form-control mt-2" id="confirmnew-pass-field" placeholder="Confirm new password" maxlength="64">
																		<div class="invalid-feedback" id="error-confnew-pass"></div>
																	</div>
																	<div class="form-row mt-2">
																		<div class="col-10">
																			<div class="custom-control custom-checkbox">
																				<input class="custom-control-input" type="checkbox" id="show-pass-toggle">
									  											<label class="custom-control-label" for="show-pass-toggle"><small>Show password</small></label>
																			</div>
																		</div>
																	</div>
																	<div class="form-row justify-content-center mt-5">	
																		<div class="col-md-8 col-sm-10 col-12">	
																			<button class="btn form-control form-control-sm btn-success" id="reset-password-btn">Reset Password</button>
																		</div>
																	</div>
																	<!---Loader--->
																	<div class="form-row mt-2" id="loader-reset" style="display: none;">
																		<h6 class="m-0 mr-1 text-success ml-auto">Please wait...</h6>
																		<img class="mr-auto" src="../resources/loader.jpg" alt="loader gif" height="20" width="20">
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
							';
						}
						else{
							echo '
								<div class="container-fluid" id="wrapper">
									<div class="row justify-content-center">
										<div class="col-md-10 col-12">
											<div class="icon-box" style="margin-top: 75px;">
												<h5 class="text-success text-center m-0" style="font-size: 150px;"><span class="fas fa-question-circle"></span></h5>
											</div>
											<h4 class="text-success text-center">Oops, something went wrong.</h4>
											<h5 class="text-success text-center">Link is expired or invalid</h5>
										</div>
									</div>
								</div>
							';
						}
					}
				}
				$reset = new Display();
				$reset->execute();
				unset($reset);
			}
			else{
				echo '
					<div class="container-fluid" id="wrapper">
						<div class="row justify-content-center">
							<div class="col-md-10 col-12">
								<div class="icon-box" style="margin-top: 75px;">
									<h5 class="text-success text-center m-0" style="font-size: 150px;"><span class="fas fa-question-circle"></span></h5>
								</div>
								<h4 class="text-success text-center">Oops, something went wrong.</h4>
								<h5 class="text-success text-center">Link is expired or invalid</h5>
							</div>
						</div>
					</div>
				';
			}
		}
		catch(PDOException|Exception $e){
			http_response_code(500);
			die($e->getMessage());
		}
	?>

	<script type='text/javascript' src='../js/jquery-3.6.0.min.js'></script>
	<script type='text/javascript' src='../js/popper.min.js'></script>
	<script type='text/javascript' src='../fontawesome/js/all.js'></script>
	<script>
		$('#new-pass-field').on('input', function(){
			if($('#new-pass-field').hasClass('is-invalid') || $('#confirmnew-pass-field').hasClass('is-invalid')){
				$('#new-pass-field').removeClass('is-invalid');
				$('#confirmnew-pass-field').removeClass('is-invalid');
				error_count--;
			}
		});
		$('#confirmnew-pass-field').on('input', function(){
			if($('#confirmnew-pass-field').hasClass('is-invalid') || $('#new-pass-field').hasClass('is-invalid')){
				$('#confirmnew-pass-field').removeClass('is-invalid');
				$('#new-pass-field').removeClass('is-invalid');
				error_count--;
			}
		});

		$(document).on('click', '#show-pass-toggle', function(){
			if(document.getElementById('show-pass-toggle').checked){
				document.getElementById('new-pass-field').type = 'text';
				document.getElementById('confirmnew-pass-field').type = 'text';	
			}
			else{
				document.getElementById('new-pass-field').type = 'password';
				document.getElementById('confirmnew-pass-field').type = 'password';	
			}
		});

		$(document).on('click', '#login-redirect', function(){
			window.location.href = 'http://' + window.location.hostname;
		});

		$(document).on('submit', '#reset-pass-form', function(e){
			e.preventDefault();

			const REGEX_PASSWORD = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\[\]\-\\\/@$!#^%*?&_=+;:'"<>.,~`}{)(|])[A-Za-zñÑ \d\[\]\-\\\/@$!#^%*?&_=+;:'"<>.,~`}{)(|]{0,}$/;
			const ERROR_EMPTY = 'Field cannot be empty';
			var error_count = 0;
			let pass = $('#new-pass-field').val();
			let confpass = $('#confirmnew-pass-field').val();

			if(pass == ''){
				$('#error-new-pass').html(ERROR_EMPTY);
				$('#new-pass-field').addClass('is-invalid');
				error_count++;
			}
			else if(pass != ''){
				if(pass.length < 8){
					$('#error-new-pass').html('Password must contain at least 8 characters');
					$('#new-pass-field').addClass('is-invalid');
					error_count++;
				}
				else if(!REGEX_PASSWORD.test(pass)){
					$('#error-new-pass').html('Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character');
					$('#new-pass-field').addClass('is-invalid');
					error_count++;
				}
				else if(pass.length > 64){
					$('#error-new-pass').html('Maximum of 64 characters only');
					$('#new-pass-field').addClass('is-invalid');
					error_count++;
				}
			}

			if(confpass == ''){
				$('#error-confnew-pass').html(ERROR_EMPTY);
				$('#confirmnew-pass-field').addClass('is-invalid');
				error_count++;
			}
			else if(confpass != ''){
				if(confpass != pass){
					$('#error-confnew-pass').html('Password does not match');
					$('#confirmnew-pass-field').addClass('is-invalid');
					error_count++;
				}
			}

			//submit if there is no error
			if(error_count == 0){
				$.ajax({
					url: 'change_password_process.php',
					method: 'POST',
					data: { password_change: pass, link: window.location.href },
					beforeSend: function(){
						$('#loader-reset').show();
						$('#reset-password-btn').attr('disabled', true);
					},
					success: function(data){
						if(data == 'SUCCESS_UPDATE'){
							$('#loader-reset').hide();
							$('#reset-password-btn').attr('disabled', false);
							$('#form-wrapper').html('<div class="row justify-content-center"><div class="col-md-10 col-12"><div class="icon-box" style="margin-top: 15px;"><h4 class="text-success text-center mb-2" style="font-size: 100px;"><span class="fas fa-check-circle"></span></h4></div><h5 class="text-success text-center mb-5">You have successfully changed your password</h5></div><div><button id="login-redirect" class="btn btn-success form-control form-control-sm">Proceed to Login</button></div></div>');
						}
						else if(data == 'SAME_PASSWORD'){
							$('#loader-reset').hide();
							$('#reset-password-btn').attr('disabled', false);
							$('#alert-same-pass').show('fade');
							setTimeout(function(){
								$('#alert-same-pass').hide('fade');
							}, 2000);
						}
					}
				});
			}
		});
	</script>
</body>
</html>