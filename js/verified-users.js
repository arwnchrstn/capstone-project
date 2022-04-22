//check the OS if user is using a computer
$(document).ready(function(){
	if(navigator.appVersion.indexOf('Android') != -1 || navigator.appVersion.indexOf('like Mac') != -1){
		alert('This site is unavailable on mobile phones, please try a different device, Thank you!');
		window.location.href = 'page_unavailable';	
		window.close();
	}
});
//when logout button is clicked
$('#logout-admin').on('click', function(){
	$('#modal-logout-confirm').modal('show');
});
$('#logout-btn').click(function(){
	$.ajax({
		url: 'includes/admin_logout',
		success: function(data){
			if(data == 'LOGOUT'){
				window.location.href = 'admin';
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//Get current time and date
setInterval(getTimeAndDate, 1000);
function getTimeAndDate(){
	var currentDate = new Date();
	const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
	const week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	var month, day, dayOfTheWeek, year, hours, minute, seconds, timePeriod;

	month = months[currentDate.getMonth()];
	day = currentDate.getDate();
	dayOfTheWeek = week[currentDate.getDay()];
	year = currentDate.getFullYear();

	if(currentDate.getHours() > 12){
		hours = currentDate.getHours() - 12;
		timePeriod = 'PM';
	}
	else{
		hours = currentDate.getHours();
		timePeriod = 'AM';
	}

	if(currentDate.getHours() == 0){
		hours = '12';
	}

	if(hours > 0 && hours < 10){
		hours = '0' + hours;
	}
	
	minute = currentDate.getMinutes();

	if(minute >= 0 && minute < 10){
		minute = '0' + minute;
	}

	seconds = currentDate.getSeconds();

	if(seconds >= 0 && seconds < 10){
		seconds = '0' + seconds;
	}

	$('#date-today').html(dayOfTheWeek + ', ' + month + " " + day + ", " + year);
	$('#current-time').html(hours + ":" + minute + ":" + seconds + " " + timePeriod);
}

function loadTable(){
	$.ajax({
		url: 'includes/load_verified_users',
		method: 'GET',
		beforeSend: function(){
			$('#loader').show();
		},
		success: function(data){
			$('#loader').hide();
			$('#verified-users-table').html(data);
			$('#verified-users-table').DataTable({
				"responsive" : true,
				"searching" : true,
				"autoWidth" : false,
				"ordering" : true,
				"order" : [[1,'asc']],
				pageLength : 10,
				lengthMenu: [[10, 20, 50, -1],[10, 20, 30, 'All']]
			});
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

$(document).ready(function(){
	getTimeAndDate();
	loadTable();
});

$(document).on('click', '.view-info', function(){
	let id = $(this).attr('id');
	$('#modal-view-info').modal('show');

	$.ajax({
		url: 'includes/view_info_modal',
		method: 'POST',
		data: { id: id },
		beforeSend: function(){
			$('#loader-view-info').show();
		},
		success: function(data){
			const json = JSON.parse(data);

			$('#loader-view-info').hide();
			$('#resident-photo').attr('src', 'uploaded image/profile pictures/' + json.picture);
			$('#full-name').html(json.fname + ' ' + json.mname + ' ' + json.lname + ' ' + json.suffix);
			$('#birthdate').html(json.bdate);
			$('#birthplace').html(json.bplace);
			$('#gender').html(json.gender);
			$('#address').html(json.address);
			$('#YOS').html(json.year_of_stay);
			$('#civil-status').html(json.civilstat);
			$('#email-address').html(json.email);
			$('#mobile-number').html(json.mobileno);
			$('#resident-voters').attr('src', 'uploaded image/voters id pictures/' + json.voters);

			$('#display-info').show();
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});
//when modal for resident info is closed
$('#modal-view-info').on('hidden.bs.modal', function(){
	$('#full-name').html('');
	$('#birthdate').html('');
	$('#birthplace').html('');
	$('#gender').html('');
	$('#civil-status').html('');
	$('#email-address').html('');
	$('#mobile-number').html('');
	$('#resident-photo').attr('src', '');
	$('#resident-voters').attr('src', '');
	$('#display-info').hide();
});

//show modal to generate certificate
$(document).on('click', '.generate-cert', function(){
	let id = $(this).attr('id');

	$('#generate-cert-modal').data('id', id);
	$('#generate-cert-modal').modal('show');
});

//toggle to show button
$('#indigency-col').on('click', function(){
	$('#generate-indigency').toggle('show');

	if($('#generate-residency').is(':visible')){
		$('#generate-residency').toggle('hide');
	}
	if($('#purpose-clearance').is(':visible')){
		$('#purpose-clearance').toggle('hide');
	}
});
$('#residency-col').on('click', function(){
	$('#generate-residency').toggle('show');

	if($('#generate-indigency').is(':visible')){
		$('#generate-indigency').toggle('hide');
	}
	if($('#purpose-clearance').is(':visible')){
		$('#purpose-clearance').toggle('hide');
	}
});
$('#clearance-col').on('click', function(){
	$('#purpose-clearance').toggle('show');

	if($('#generate-residency').is(':visible')){
		$('#generate-residency').toggle('hide');
	}
	if($('#generate-indigency').is(':visible')){
		$('#generate-indigency').toggle('hide');
	}
});

$('#purpose-clearance-field').on('input', function(){
	if($('#purpose-clearance-field').hasClass('is-invalid')){
		$('#purpose-clearance-field').removeClass('is-invalid');
	}
});

//reset button when modal for generate cert is closed
$('#generate-cert-modal').on('hidden.bs.modal', function(){
	$('#generate-cert-modal').data('id', '');
	$('#generate-indigency').hide();
	$('#generate-residency').hide();
	$('#purpose-clearance').hide();
	$('#purpose-clearance-field').val('');
});

//generate certicate when btn is clicked
//generate indigency
$('#generate-indigency-btn').on('click', function(){
	$.ajax({
		url: 'includes/generate-certificate',
		method: 'POST',
		data: { id: $('#generate-cert-modal').data('id'), req_type: 'IND' },
		beforeSend: function(){
			$('#generate-indigency-btn').attr('disabled', true);
			$('#spinner-generate-indigency').show();
		},
		success: function(data){
			if(data.includes('SUCCESS_GENERATE_PDF')){
				$('#generate-indigency-btn').attr('disabled', false);
				$('#spinner-generate-indigency').hide();
				$('#generate-cert-modal').modal('hide');
				$('#modal-success-generate').modal('show');

				const b64toBlob = (b64Data, contentType='', sliceSize=512) => {
					const byteCharacters = atob(b64Data);
					const byteArrays = [];

				 	for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
				    	const slice = byteCharacters.slice(offset, offset + sliceSize);

				    	const byteNumbers = new Array(slice.length);
				    	for (let i = 0; i < slice.length; i++){
				     		byteNumbers[i] = slice.charCodeAt(i);
				    	}
				    	const byteArray = new Uint8Array(byteNumbers);
				    	byteArrays.push(byteArray);
				  }
				  const blob = new Blob(byteArrays, {type: contentType});
				  return blob;
				}
				let pdf = data.replace('SUCCESS_GENERATE_PDF', '');

				const blob = b64toBlob(pdf, 'application/pdf');
				const url = URL.createObjectURL(blob);
				window.open(url);
			}
			else{
				$('#generate-indigency-btn').attr('disabled', false);
				$('#spinner-generate-indigency').hide();
				alert('Oops, an error occurred.');
				console.error(data);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//generate residency
$('#generate-residency-btn').on('click', function(){
	$.ajax({
		url: 'includes/generate-certificate',
		method: 'POST',
		data: { id: $('#generate-cert-modal').data('id'), req_type: 'RES' },
		beforeSend: function(){
			$('#generate-residency-btn').attr('disabled', true);
			$('#spinner-generate-residency').show();
		},
		success: function(data){
			if(data.includes('SUCCESS_GENERATE_PDF')){
				$('#generate-residency-btn').attr('disabled', false);
				$('#spinner-generate-residency').hide();
				$('#generate-cert-modal').modal('hide');
				$('#modal-success-generate').modal('show');

				const b64toBlob = (b64Data, contentType='', sliceSize=512) => {
					const byteCharacters = atob(b64Data);
					const byteArrays = [];

				 	for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
				    	const slice = byteCharacters.slice(offset, offset + sliceSize);

				    	const byteNumbers = new Array(slice.length);
				    	for (let i = 0; i < slice.length; i++){
				     		byteNumbers[i] = slice.charCodeAt(i);
				    	}
				    	const byteArray = new Uint8Array(byteNumbers);
				    	byteArrays.push(byteArray);
				  }
				  const blob = new Blob(byteArrays, {type: contentType});
				  return blob;
				}
				let pdf = data.replace('SUCCESS_GENERATE_PDF', '');

				const blob = b64toBlob(pdf, 'application/pdf');
				const url = URL.createObjectURL(blob);
				window.open(url);
			}
			else{
				$('#generate-residency-btn').attr('disabled', false);
				$('#spinner-generate-residency').hide();
				alert('Oops, an error occurred.');
				console.error(data);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//generate brgy clearance
$('#generate-clearance-btn').on('click', function(){
	const REGEX_PURPOSE = /^[A-Za-z ]+$/;
	let purpose = $('#purpose-clearance-field').val().trim();

	if(purpose == ''){
		$('#error-purpose-clearance').html('Field cannot be empty');
		$('#purpose-clearance-field').addClass('is-invalid');
	}
	else if (purpose != ''){
		if(!REGEX_PURPOSE.test(purpose) || purpose.length > 256){
			$('#error-purpose-clearance').html('Invalid input');
			$('#purpose-clearance-field').addClass('is-invalid');
		}
		else{
			$.ajax({
				url: 'includes/generate-certificate',
				method: 'POST',
				data: { id: $('#generate-cert-modal').data('id'), purpose: purpose },
				beforeSend: function(){
					$('#generate-clearance-btn').attr('disabled', true);
					$('#spinner-generate-clearance').show();
				},
				success: function(data){
					if(data.includes('SUCCESS_GENERATE_PDF')){
						$('#generate-clearance-btn').attr('disabled', false);
						$('#spinner-generate-clearance').hide();
						$('#generate-cert-modal').modal('hide');
						$('#modal-success-generate').modal('show');

						const b64toBlob = (b64Data, contentType='', sliceSize=512) => {
							const byteCharacters = atob(b64Data);
							const byteArrays = [];

						 	for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
						    	const slice = byteCharacters.slice(offset, offset + sliceSize);

						    	const byteNumbers = new Array(slice.length);
						    	for (let i = 0; i < slice.length; i++){
						     		byteNumbers[i] = slice.charCodeAt(i);
						    	}
						    	const byteArray = new Uint8Array(byteNumbers);
						    	byteArrays.push(byteArray);
						  }
						  const blob = new Blob(byteArrays, {type: contentType});
						  return blob;
						}
						let pdf = data.replace('SUCCESS_GENERATE_PDF', '');

						const blob = b64toBlob(pdf, 'application/pdf');
						const url = URL.createObjectURL(blob);
						window.open(url);
					}
					else{
						$('#generate-clearance-btn').attr('disabled', false);
						$('#spinner-generate-clearance').hide();
						alert('Oops, an error occurred.');
						console.error(data);
					}
				},
				error: function(xhr){
					alert('Oops, an error occurred.');
		        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
		    	}
			});
		}
	}
});