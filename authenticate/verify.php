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
	<title>Verify Email</title>
	<style>
		html{
			min-width: 280px;
			max-width: 2000px;
		}
		#wrapper{
			height: 100vh;
			background-color: rgba(69, 176, 111, 0.3);
		}
	</style>
</head>
<body>
	<?php 
		try{
			if(isset($_GET['key'])){
				Class Functions extends DbConn{
					public function execute(){
						//check if vkey is valid
						$stmt = $this->connect()->prepare("SELECT verification_key FROM verification WHERE verification_key = ? LIMIT 1");
						$stmt->execute([$_GET['key']]);
						$result = $stmt->fetch();

						if($result){
							$sql = "SELECT isEmailVerified FROM verification WHERE verification_key = ? LIMIT 1";
							$stmt2 = $this->connect()->prepare($sql);
							$stmt2->execute([$_GET['key']]);
							$result2 = $stmt2->fetch();

							if($result2){
								if($result2['isEmailVerified'] == 0){
									//update email status to verified
									$this->connect()->prepare("UPDATE verification SET isEmailVerified = ? WHERE verification_key = ?")->execute([1,$_GET['key']]);

									echo '
										<div class="container-fluid" id="wrapper">
											<div class="row justify-content-center">
												<div class="col-md-10 col-12">
													<div class="icon-box" style="margin-top: 75px;">
														<h5 class="text-success text-center m-0" style="font-size: 150px;"><span class="fas fa-check-circle"></span></h5>
													</div>
													<h4 class="text-success text-center">Your email is verified, you can now login to your account</h4>
												</div>
												<div class="col-md-5 col-9" style="margin-top: 80px;">
													<button class="btn btn-success form-control">Proceed to Login</button>
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
														<h5 class="text-success text-center m-0" style="font-size: 150px;"><span class="fas fa-check-circle"></span></h5>
													</div>
													<h4 class="text-success text-center">Looks Good!</h4>
													<h5 class="text-success text-center">Your email is already verified</h5>
												</div>
												<div class="col-md-5 col-9" style="margin-top: 80px;">
													<button class="btn btn-success form-control">Proceed to Login</button>
												</div>
											</div>
										</div>	
									';								
								}
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
												<h5 class="text-success text-center">Invalid confirmation link</h5>
											</div>
										</div>
									</div>
								';
							}
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
											<h5 class="text-success text-center">Invalid confirmation link</h5>
										</div>
									</div>
								</div>
							';
						}
					}
				}
				$verify = new Functions();
				$verify->execute();
				unset($verify);
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
		$(document).on('click', '.btn-success', function(){
			window.location.href = 'http://' + window.location.hostname;
		});
	</script>
</body>
</html>