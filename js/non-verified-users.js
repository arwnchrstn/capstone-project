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

function loadTable(status){
	$.ajax({
		url: 'includes/load_non_verified_users',
		method: 'POST',
		data: { status: status },
		beforeSend: function(){
			$('#loader').show();
		},
		success: function(data){
			$('#loader').hide();
			$('#non-verified-users-table').html(data);
			$('#non-verified-users-table').DataTable({
				"responsive" : true,
				"searching" : false,
				"ordering" : true,
				"order" : [[3,'asc']],
 				"destroy" : true,
				"autoWidth" : false,
				pageLength : 10,
		   		lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
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
	//default load
	loadTable(1);
});

//load table when the btn group is clicked
$('#verified-email').on('click', function(){
	if(!$('#verified-email').hasClass('active')){
		if($.fn.DataTable.isDataTable($('#non-verified-users-table'))){
			$('#non-verified-users-table').DataTable().destroy();
		}
		loadTable(1);
	}
});
$('#unverified-email').on('click', function(){
	if(!$('#unverified-email').hasClass('active')){
		if($.fn.DataTable.isDataTable($('#non-verified-users-table'))){
			$('#non-verified-users-table').DataTable().destroy();
		}
		loadTable(0);
	}
});

//when view infor btn is clicked
$(document).on('click', '.view-info', function(){
	let id = $(this).attr('id');

	$('#modal-view-info').modal('show');
	$.ajax({
		url: 'includes/view_info_modal',
		method: 'POST',
		data: { id: id },
		beforeSend: function(){
			$('#loader-view-info').show();
			$('#display-info').hide();
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

//when verify btn is clicked
$(document).on('click', '.verify', function(){
	let id = $(this).attr('id');
	$('#modal-verify-confirm').modal('show');
	$('#modal-verify-confirm').data('id', id);
});

$('#verify-btn').on('click', function(){
	$.ajax({
		url: 'includes/verify_resident_account',
		method: 'POST',
		data: { id: $('#modal-verify-confirm').data('id') },
		beforeSend: function(){
			$('#loader').show();
			$('.verify').attr('disabled', true);
			$('#modal-verify-confirm').modal('hide');
		},
		success: function(data){
			if(data == 'SUCCESS_VERIFY'){
				$('.verify').attr('disabled', false);
				$('#non-verified-users-table').DataTable().row('#'+$('#modal-verify-confirm').data('id')).remove().draw();
				$('#modal-verify-confirm').data('id', '');
				$('#loader').hide();
				$('#success-process').toast('show');
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data);
				$('#loader').hide();
				$('.verify').attr('disabled', false);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//delete account when older or equal to 10 days
$(document).on('click', '.delete-account', function(){
	let id = $(this).attr('id');
	
	$.ajax({
		url: 'includes/delete-unverified-email',
		method: 'POST',
		data: { id_delete: id },
		beforeSend: function(){
			$('#loader').show();
			$('.delete-account').attr('disabled', true);
		},
		success: function(data){
			if(data == 'SUCCESS_DELETE'){
				$('#loader').hide();
				$('.delete-account').attr('disabled', false);
				$('#non-verified-users-table').DataTable().row('#'+id).remove().draw();
				$('#confirm-text').html('<span class="fas fa-check-circle"></span> Account Deleted');
				$('#success-process').toast('show');
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data);
				$('#loader').hide();
				$('.delete-account').attr('disabled', false);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});