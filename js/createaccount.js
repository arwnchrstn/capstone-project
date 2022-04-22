var error_count;

//prevent non-numeric inputs in mobile number
$('#create-contactno-field').keypress(function (e) {
    if (e.keyCode < 48 || e.keyCode > 57){
        e.preventDefault();
    }
});

//prevent typing letters in birthdate
$('#create-bdate-field').keypress(function (e) {
	if ((e.keyCode < 48 || e.keyCode > 57) && e.keyCode != 47){
        e.preventDefault();
    }
});

//prevent non numeric chars in year field
$('#year-of-stay-field').keypress(function (e) {
    if (e.keyCode < 48 || e.keyCode > 57){
        e.preventDefault();
    }
});

//show password toggle
$('#show-pass-toggle').on('click', function(){
	if(document.getElementById('show-pass-toggle').checked){
		document.getElementById('create-pass-field').type = 'text';
		document.getElementById('create-confpass-field').type = 'text';
	}
	else{
		document.getElementById('create-pass-field').type = 'password';	
		document.getElementById('create-confpass-field').type = 'password';
	}
});
//---------------------------------------------------------------------------
const REGEX_EMAIL = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
const REGEX_PASSWORD = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\[\]\-\\\/@$!#^%*?&_=+;:'"<>.,~`}{)(|])[A-Za-zñÑ \d\[\]\-\\\/@$!#^%*?&_=+;:'"<>.,~`}{)(|]{0,}$/;
const REGEX_NAME = /^[A-Za-zñÑ\- ]+$/;
const REGEX_BPLACE = /^[A-Za-zñÑ., ]+$/;
const REGEX_MOBILE = /^[0-9]+$/;
const REGEX_ADDRESS = /^[A-Za-z0-9ñÑ.,'#@%&/\- ]+$/;
const REGEX_DATE = /(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)\d\d/i;
let user_email = $('#create-email-field');
let conf_pass = $('#create-confpass-field');
let user_pass = $('#create-pass-field');
let fname = $('#create-fname-field');
let mname = $('#create-mname-field');
let lname = $('#create-lname-field');
let bplace = $('#create-bplace-field');
let house_address = $('#create-address-field');
let mobile = $('#create-contactno-field');
let bdate = $('#create-bdate-field');
let year_of_stay = $('#year-of-stay-field');
let date_today = new Date();
//let user_photo = $('#profile-upload').get(0).files.length;
const ERROR_EMPTY = 'Field cannot be empty';

//user email validation
user_email.on('input', function(e) {
	if(e.target.value === ""){
		$('#create-email-error').html(ERROR_EMPTY);
		$('#create-email-field').addClass('is-invalid');
	}
	else if (e.target.value !== ""){
		if(!REGEX_EMAIL.test(e.target.value)){
			$('#create-email-error').html('Please enter a valid email');
			$('#create-email-field').addClass('is-invalid');
		}
		else if(e.target.value.length > 60){
			$('#create-email-error').html('Maximum of 60 characters only');
			$('#create-email-field').addClass('is-invalid');
		}
		else{
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
	
});

//user password validation
user_pass.on('input', function(e) {
	if(e.target.value === ""){
		$('#create-pass-error').html(ERROR_EMPTY);
		$('#create-pass-field').addClass('is-invalid');
	}
	else if(e.target.value !== ""){
		if(!REGEX_PASSWORD.test(e.target.value) || e.target.value.length < 8){
			$('#create-pass-error').html('Password must be at least 8 characters and must contain at least one number, one uppercase letter, one lowercase letter, and one special character');
			$('#create-pass-field').addClass('is-invalid');
		}
		else{
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
			$('#create-confpass-field').removeClass('is-invalid');
		}
	}
});

//confirm password validation
conf_pass.on('input', function(e) {
	if(e.target.value === ""){
		$('#create-confpass-error').html(ERROR_EMPTY);
		$('#create-confpass-field').addClass('is-invalid');
	}
	else if(e.target.value !== ""){
		if(e.target.value !== user_pass.val().trim()){
			$('#create-confpass-error').html('Password does not match');
			$('#create-confpass-field').addClass('is-invalid');
		}
		else{
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
});

//name validation for user
//first name
fname.on('input', function(e){
	if(e.target.value === ""){
		$('#create-fname-error').html(ERROR_EMPTY);
		$('#create-fname-field').addClass('is-invalid');
	}
	else if(e.target.value !== ""){
		if(!REGEX_NAME.test(e.target.value)){
			$('#create-fname-error').html('(A-Z and -) are only allowed for this field');
			$('#create-fname-field').addClass('is-invalid');
		}
		else if(e.target.value.length > 50){
			$('#create-fname-error').html('Maximum of 50 characters only');
			$('#create-fname-field').addClass('is-invalid');
		}
		else{
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
})

//middle name
mname.on('input', function(e){
	if(e.target.value === ""){
		if(this.classList.contains('is-invalid')){
			this.classList.remove('is-invalid');
		}
	}
	else if(e.target.value !== ""){
		if(!REGEX_NAME.test(e.target.value)){
			$('#create-mname-error').html('(A-Z and -) are only allowed for this field');
			$('#create-mname-field').addClass('is-invalid');
		}
		else if(e.target.value.length > 50){
			$('#create-mname-error').html('Maximum of 50 characters only');
			$('#create-mname-field').addClass('is-invalid');
		}
		else {
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
});

//last name
lname.on('input', function(e){
	if(e.target.value === ""){
		$('#create-lname-error').html(ERROR_EMPTY);
		$('#create-lname-field').addClass('is-invalid');
	}
	else if(e.target.value !== ""){
		if(!REGEX_NAME.test(e.target.value)){
			$('#create-lname-error').html('(A-Z and -) are only allowed for this field');
			$('#create-lname-field').addClass('is-invalid');
		}
		else if(e.target.value.length > 50){
			$('#create-lname-error').html('Maximum of 50 characters only');
			$('#create-lname-field').addClass('is-invalid');
		}
		else {
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
});

//birthdate validation
bdate.on('input', function(e){
	if(e.target.value === ""){
		$('#create-bdate-error').html(ERROR_EMPTY);
		$('#create-bdate-field').addClass('is-invalid');
	}
	else if(e.target.value !== ""){
		if(!REGEX_DATE.test(e.target.value) || parseInt(e.target.value.substr(-4)) > date_today.getFullYear() ){
			$('#create-bdate-error').html('Invalid date, please double check your entry and follow this format (mm/dd/yyyy)');
			$('#create-bdate-field').addClass('is-invalid');
		}
		else if(e.target.value.length > 10){
			$('#create-bdate-error').html('Invalid date');
			$('#create-bdate-field').addClass('is-invalid');
		}
		else {
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
});

//birthplace validation
bplace.on('input', function(e) {
	if(e.target.value === ""){
		$('#create-bplace-error').html(ERROR_EMPTY);
		$('#create-bplace-field').addClass('is-invalid');
	}
	else if(e.target.value != ""){
		if(!REGEX_BPLACE.test(e.target.value)){
			$('#create-bplace-error').html('(A-Z .,) are only allowed for this field');
			$('#create-bplace-field').addClass('is-invalid');
		}
		else if(e.target.value.length < 5){
			$('#create-bplace-error').html('Birthplace is too short');
			$('#create-bplace-field').addClass('is-invalid');
		}
		else if(e.target.value.length > 60){
			$('#create-bplace-error').html('Maximum of 60 characters only');
			$('#create-bplace-field').addClass('is-invalid');
		}
		else {
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
});

//house address validation
house_address.on('input', function(e){
	if(e.target.value === ""){
		$('#create-address-error').html(ERROR_EMPTY);
		$('#create-address-field').addClass('is-invalid');
	}
	else if(e.target.value !== ""){
		if(!REGEX_ADDRESS.test(e.target.value)){
			$('#create-address-error').html("(A-Z .,\'#@%&/-) are only allowed for this field");
			$('#create-address-field').addClass('is-invalid');
		}
		else if(e.target.value.length < 5){
			$('#create-address-error').html('Address is too short');
			$('#create-address-field').addClass('is-invalid');
		}
		else if(e.target.value.length > 256){
			$('#create-address-error').html('Maximum of 256 characters only');
			$('#create-address-field').addClass('is-invalid');
		}
		else {
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
});

//year of stay validation
year_of_stay.on('input', function(e){
	if(e.target.value === ""){
		$('#year-of-stay-error').html(ERROR_EMPTY);
		$('#year-of-stay-field').addClass('is-invalid');
	}
	else if(e.target.value !== ""){
		if(!REGEX_MOBILE.test(e.target.value) || e.target.value.length != 4 || parseInt(e.target.value) === 0 || e.target.value > date_today.getFullYear()){
			$('#year-of-stay-error').html('Invalid Input');
			$('#year-of-stay-field').addClass('is-invalid');
		}
		else {
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
});

//mobile number validation
mobile.on('input', function(e){
	if(e.target.value === ""){
		$('#create-contactno-error').html(ERROR_EMPTY);
		$('#create-contactno-field').addClass('is-invalid');
	}
	else if(e.target.value !== ""){
		if(!REGEX_MOBILE.test(e.target.value) || e.target.value.length != 11 || e.target.value.charAt(0)+e.target.value.charAt(1) != '09'){
			$('#create-contactno-error').html('Mobile number should start in 09 and must be 11 digits');
			$('#create-contactno-field').addClass('is-invalid');
		}
		else {
			if(this.classList.contains('is-invalid')){
				this.classList.remove('is-invalid');
				$('#proceed-create-btn').removeClass('is-invalid');
			}
		}
	}
});

//terms checkbox validation
$('#terms-checkbox').on('change', function(){
	if($('#terms-checkbox').hasClass('is-invalid')){
		$('#terms-checkbox').removeClass('is-invalid');
		$('#proceed-create-btn').removeClass('is-invalid');
		error_count--;
	}
});
//certify checkbox validation
$('#certify-checkbox').on('change', function(){
	if($('#certify-checkbox').hasClass('is-invalid')){
		$('#certify-checkbox').removeClass('is-invalid');
		$('#proceed-create-btn').removeClass('is-invalid');
		error_count--;
	}
});
$('#voters-upload').on('change', function(){
	if($('#voters-upload').hasClass('is-invalid')){
		$('#voters-upload').removeClass('is-invalid');
		$('#proceed-create-btn').removeClass('is-invalid');
		error_count--;
	}
});

//----------------------------------------------------------------------------------
//when proceed button is clicked
$('#proceed-create-btn').on('click', function(){
	//function for converting date format
	function dateConvert(bdate){
		let date = new Date(bdate);
		let day = date.getDate();
		let month = date.getMonth() + 1;
		let year = date.getFullYear();
		let fullDate = "";

		switch (month) {
			case 1:
				fullDate = "January " + day + ", " + year;
				break;
			case 2:
				fullDate = "February " + day + ", " + year;
				break;
			case 3:
				fullDate = "March " + day + ", " + year;
				break;
			case 4:
				fullDate = "April " + day + ", " + year;
				break;
			case 5:
				fullDate = "May " + day + ", " + year;
				break;
			case 6:
				fullDate = "June " + day + ", " + year;
				break;
			case 7:
				fullDate = "July " + day + ", " + year;
				break;
			case 8:
				fullDate = "August " + day + ", " + year;
				break;
			case 9:
				fullDate = "September " + day + ", " + year;
				break;
			case 10:
				fullDate = "October " + day + ", " + year;
				break;
			case 11:
				fullDate = "November " + day + ", " + year;
				break;
			case 12:
				fullDate = "December " + day + ", " + year;
				break;
		}
		return fullDate;
	}

	let user_email = $('#create-email-field').val().trim();
	let conf_pass = $('#create-confpass-field').val();
	let user_pass = $('#create-pass-field').val();
	let fname = $('#create-fname-field').val().trim();
	let mname = $('#create-mname-field').val().trim();
	let lname = $('#create-lname-field').val().trim();
	let suffix = $('#create-suffix-field').val();
	let bplace = $('#create-bplace-field').val().trim();
	let house_address = $('#create-address-field').val().trim();
	let mobile = $('#create-contactno-field').val().trim();
	let bdate = $('#create-bdate-field').val();
	let gender = $('#gender-select').val();
	let month_of_stay = $('#year-of-stay-select option:selected').text();
	let year_of_stay = $('#year-of-stay-field').val().trim();
	let civilstat = $('#civilstat-select').val();
	let terms = $('#terms-checkbox').is(':checked');
	let certify = $('#certify-checkbox').is(':checked');
	//let user_photo = $('#profile-upload').get(0).files.length;
	let voters_photo = $('#voters-upload').get(0).files.length;
	const ERROR_EMPTY = 'Field cannot be empty';
	error_count = 0;

	//user email validation
	if(user_email === ""){
		$('#create-email-error').html(ERROR_EMPTY);
		$('#create-email-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if (user_email !== ""){
		//check if email exist
		$.ajax({
			url: 'includes/create_account_process',
			method: 'POST',
			data: { checkEmail: user_email },
			async: false,
			success: function(data){
				if(parseInt(data) === 1){
					$('#create-email-error').html('Email address is already taken');
					$('#create-email-field').addClass('is-invalid');
					$('#proceed-create-btn').addClass('is-invalid');
					error_count++;
				}
			},
			error: function(xhr){
				alert('Oops, an error occurred.');
	        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
	    	}
		});

		if(!REGEX_EMAIL.test(user_email)){
			$('#create-email-error').html('Please enter a valid email');
			$('#create-email-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
		else if(user_email.length > 60){
			$('#create-email-error').html('Maximum of 60 characters only');
			$('#create-email-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//user password validation
	if(user_pass === ""){
		$('#create-pass-error').html(ERROR_EMPTY);
		$('#create-pass-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if(user_pass !== ""){
		if(!REGEX_PASSWORD.test(user_pass) || user_pass.length < 8){
			$('#create-pass-error').html('Password must be at least 8 characters and must contain at least one number, one uppercase letter, one lowercase letter, and one special character');
			$('#create-pass-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//confirm password validation
	if(conf_pass == ""){
		$('#create-confpass-error').html(ERROR_EMPTY);
		$('#create-confpass-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if(conf_pass != ""){
		if(user_pass != conf_pass){
			$('#create-confpass-error').html('Password does not match');
			$('#create-confpass-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//name validation for user
	//first name
	if(fname === ""){
		$('#create-fname-error').html(ERROR_EMPTY);
		$('#create-fname-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if(fname !== ""){
		if(!REGEX_NAME.test(fname)){
			$('#create-fname-error').html('(A-Z and -) are only allowed for this field');
			$('#create-fname-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
		else if(fname.length > 50){
			$('#create-fname-error').html('Maximum of 50 characters only');
			$('#create-fname-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//middle name
	if(mname !== ""){
		if(!REGEX_NAME.test(mname)){
			$('#create-mname-error').html('(A-Z and -) are only allowed for this field');
			$('#create-mname-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
		else if(mname.length > 50){
			$('#create-mname-error').html('Maximum of 50 characters only');
			$('#create-mname-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//last name
	if(lname === ""){
		$('#create-lname-error').html(ERROR_EMPTY);
		$('#create-lname-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if(lname !== ""){
		if(!REGEX_NAME.test(lname)){
			$('#create-lname-error').html('(A-Z and -) are only allowed for this field');
			$('#create-lname-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
		else if(lname.length > 50){
			$('#create-lname-error').html('Maximum of 50 characters only');
			$('#create-lname-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//birthdate validation
	if(bdate === ""){
		$('#create-bdate-error').html(ERROR_EMPTY);
		$('#create-bdate-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if(bdate !== ""){
		if(!REGEX_DATE.test(bdate)){
			$('#create-bdate-error').html('Invalid date, please double check your entry and follow this format (mm/dd/yyyy)');
			$('#create-bdate-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
		else if(bdate.length > 10){
			$('#create-bdate-error').html('Invalid date');
			$('#create-bdate-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//birthplace validation
	if(bplace === ""){
		$('#create-bplace-error').html(ERROR_EMPTY);
		$('#create-bplace-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if(bplace !== ""){
		if(!REGEX_BPLACE.test(bplace)){
			$('#create-bplace-error').html('(A-Z .,) are only allowed for this field');
			$('#create-bplace-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
		else if(bplace.length < 5){
			$('#create-bplace-error').html('Birthplace is too short');
			$('#create-bplace-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
		else if(bplace.length > 60){
			$('#create-bplace-error').html('Maximum of 60 characters only');
			$('#create-bplace-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//house address validation
	if(house_address == ""){
		$('#create-address-error').html(ERROR_EMPTY);
		$('#create-address-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if(house_address != ""){
		if(!REGEX_ADDRESS.test(house_address)){
			$('#create-address-error').html('(A-Z .,\'#@%&/-) are only allowed for this field');
			$('#create-address-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
		else if(house_address.length < 5){
			$('#create-address-error').html('Address is too short');
			$('#create-address-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
		else if(house_address.length > 256){
			$('#create-address-error').html('Maximum of 256 characters only');
			$('#create-address-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//year of stay validation
	if(year_of_stay === ""){
		$('#year-of-stay-error').html(ERROR_EMPTY);
		$('#year-of-stay-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if(year_of_stay !== ""){
		if(!REGEX_MOBILE.test(year_of_stay) || year_of_stay.length != 4 || parseInt(year_of_stay) === 0 || year_of_stay > date_today.getFullYear()){
			$('#year-of-stay-error').html('Invalid Input');
			$('#year-of-stay-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//mobile number validation
	if(mobile == ""){
		$('#create-contactno-error').html(ERROR_EMPTY);
		$('#create-contactno-field').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	else if(mobile != ""){
		if(!REGEX_MOBILE.test(mobile) || mobile.length != 11 || mobile.charAt(0)+mobile.charAt(1) != '09'){
			$('#create-contactno-error').html('Mobile number should start in 09 and must be 11 digits');
			$('#create-contactno-field').addClass('is-invalid');
			$('#proceed-create-btn').addClass('is-invalid');
			error_count++;
		}
	}

	//voters upload valiation
	if(voters_photo === 0){
		$('#voters-photo-error').html('Please upload a photo');
		$('#voters-upload').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}

	//check if the terms and condition box is checked
	if(!terms){
		$('#terms-checkbox').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}
	//check if the certify box is checked
	if(!certify){
		$('#certify-checkbox').addClass('is-invalid');
		$('#proceed-create-btn').addClass('is-invalid');
		error_count++;
	}

	//if error count is 0
	//show modal and show info
	if(error_count == 0){
		//name
		if(suffix == 'N/A'){
			$('#full-name').html((fname + " " + mname + " " + lname).toUpperCase());
		}
		else if(suffix != 'N/A'){
			if(suffix == 'Jr' || suffix == 'Sr'){
				$('#full-name').html((fname + " " + mname + " " + lname + " " + suffix + ".").toUpperCase());
			}
			else{
				$('#full-name').html((fname + " " + mname + " " + lname + " " + suffix).toUpperCase());
			}
		}

		//birthdate
		$('#birthdate').html(dateConvert(bdate).toUpperCase());

		//birthplace
		$('#birthplace').html(bplace.toUpperCase());

		//gender
		$('#gender').html(gender.toUpperCase());

		//civil status
		$('#civil-status').html(civilstat.toUpperCase());

		//address
		$('#address').html((house_address + ", " + " Bigaa, City of Cabuyao, Laguna").toUpperCase());

		//year of stay
		$('#year-of-stay').html(month_of_stay + ' ' + year_of_stay);

		//mobile number
		$('#mobile-number').html(mobile);

		//email address
		$('#email-address').html(user_email.toLowerCase());

		//display photo in modal
		$('#user-photo-confirm').attr('src', $('#image-container').attr('src'));

		//display voter's id in modal
		$('#user-voters-confirm').attr('src', $('#image-container-voters').attr('src'));

		//display modal with information
		$('#modal-confirm-info').modal('show');
	}
});
//send ajax request when submit button is clicked
$('#register-btn').on('click', function(){
    let user_email = $('#create-email-field').val().trim();
	let user_pass = $('#create-pass-field').val();
	let fname = $('#create-fname-field').val().trim();
	let mname = $('#create-mname-field').val().trim();
	let lname = $('#create-lname-field').val().trim();
	let suffix = $('#create-suffix-field').val();
	let bplace = $('#create-bplace-field').val().trim();
	let house_address = $('#create-address-field').val().trim();
	let mobile = $('#create-contactno-field').val().trim();
	let bdate = $('#create-bdate-field').val().trim();
	let gender = $('#gender-select').val();
	let year_of_stay = $('#year-of-stay-field').val().trim();
	let month_of_stay = $('#year-of-stay-select').val();
	let civilstat = $('#civilstat-select').val();
    
	//format bdate
	bdate = bdate.split('/');
	bdate = `${bdate[2]}-${bdate[0]}-${bdate[1]}`;

	$.ajax({
		url: 'includes/create_account_process',
		method: 'POST',
		data: { profile_pic: $('#image-container').attr('src'), voters_pic: $('#image-container-voters').attr('src'), email: user_email.toLowerCase(), password: user_pass, fname: fname.toUpperCase(), mname: mname.toUpperCase(), lname: lname.toUpperCase(), suffix: suffix.toUpperCase(), bdate: bdate, bplace: bplace.toUpperCase(), gender: gender.toUpperCase(), civilstat: civilstat.toUpperCase(), year_of_stay: `${year_of_stay}-${month_of_stay}-01`, address: house_address.toUpperCase() + " BIGAA, CITY OF CABUYAO, LAGUNA",  mobileno: mobile },
		beforeSend: function(){
			$('#modal-confirm-info').modal('hide');
			$('#modal-loading-process').modal('show');
			$('#proceed-create-btn').attr('disabled', true);
		},
		success: function(data){
			if(data == "SUCCESS_CREATE"){
				$('#modal-loading-process').modal('hide');
				$('#proceed-create-btn').attr('disabled', true);
				$('#createacct-form')[0].reset();
				$('#image-container').attr('src', 'uploaded image/default_profile.png');
				$('#image-container-voters').attr('src', 'resources/upload_id_icon.png');
				$('#modal-success-create').modal('show');
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data);
				$('#modal-loading-process').modal('hide');
				$('#proceed-create-btn').attr('disabled', false);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});
//redirect to homepage
$('#modal-success-create').on('hidden.bs.modal', function(){
	window.location.href = 'https://' + window.location.hostname;
});

//if voters id is uploaded, load the image
$('#voters-upload').on('input', function(){
	const file = this.files[0];

	if(file){
		const reader = new FileReader();
		reader.addEventListener('load', function(){
			$('#image-container-voters').attr('src', this.result);
		});

		reader.readAsDataURL(file);
	}
	else{
		$('#voters-upload').val(null);
		$('#image-container-voters').attr('src', 'resources/upload_id_icon.png');
	}
});

//if profile photo is uploaded, crop the image
$(document).ready(function(){
    var $modal = $('#modal-crop-img');
    var crop_image = document.getElementById('image-crop-container');
    var cropper;

    $('#profile-upload').on('input',function(){
        var files = event.target.files;
        var done = function(url){
            crop_image.src = url;
            $modal.modal('show');
        };
        if(files && files.length > 0){
            var reader = new FileReader();
            reader.onload = function(event){
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
        else{
        	$('#profile-upload').val(null);
        	$('#image-container').attr('src', 'uploaded image/default_profile.png');
        }
    });
    $modal.on('shown.bs.modal', function() {
        cropper = new Cropper(crop_image, {
            aspectRatio: 1,
            viewMode: 3
        });
    }).on('hidden.bs.modal', function(){
        cropper.destroy();
        cropper = null;
    });


    //if cancel crop is clicked, clear file input
    $('#cancel-crop').on('click', function(){
    	$('#profile-upload').val(null);
    });
    //if clear photo button is clicked, clear file input
    $('#clear-photo').on('click', function(){
    	$('#profile-upload').val(null);
    	$('#image-container').attr('src', 'uploaded image/default_profile.png');
    });
    //if clear photo voters button is clicked, clear file input
    $('#clear-photo-voters').on('click', function(){
    	$('#voters-upload').val(null);
    	$('#image-container-voters').attr('src', 'resources/upload_id_icon.png');
    });

    //if crop button is clicked, crop the image and display
    $('#crop-image-display').on('click', function(){
    	var canvas = cropper.getCroppedCanvas({
			width:150,
			height:150
		});

		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
				$modal.modal('hide');
				$('#image-container').attr('src', base64data);
			};
		});
    });
});