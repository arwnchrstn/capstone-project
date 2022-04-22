var error_count;

if($('#nav-1').hasClass('active-state'))
	$('#nav-1').removeClass('active-state');

$('#nav-2').addClass('active-state');

//check the OS if user is using a computer
$(document).ready(function(){
	if(navigator.appVersion.indexOf('Android') != -1 || navigator.appVersion.indexOf('like Mac') != -1){
		alert('This site is unavailable on mobile phones, please try a different device, Thank you!');
		window.location.href = 'page_unavailable';
		window.close();
	}
});
//prevent non-numeric inputs on control number
$('#ctrl-no').keypress(function(e){
	if(e.keyCode < 48 || e.keyCode > 57){
		e.preventDefault();
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

//function for retrieving data from the databade for admin table
function getTableData(){
	$.ajax({
		url: 'includes/load_admin_accounts',
		method: 'GET',
		beforeSend: function(){
			$('#loader').show();
		},
		success: function(data){
			$('#loader').hide();
			$('#admin-table').html(data);
			if($.fn.DataTable.isDataTable('#admin-table')){
				$('#admin-table').DataTable().destroy();
			}
			$('#admin-table').DataTable({
				"responsive": true,
				"searching": false,
				"destroy": true,
				"paging": true,
				"ordering": false,
				pageLength: 5,
				lengthMenu: [[5,10,20,-1],[5,10,20,'All']]
			});
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

//function for loading the current control number for brgy clearance
function getCtrlNumber(){
	//load the current control number for brgy clerance
	$.ajax({
		url: 'includes/load_control_number',
		method: 'GET',
		success: function(data){
			$('#ctrl-no').val(data);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

$(document).ready(function(){
	getTimeAndDate();
	//load table for admin accounts
	$.ajax({
		url: 'includes/load_admin_accounts',
		method: 'GET',
		beforeSend: function(){
			$('#loader').show();
		},
		success: function(data){
			$('#loader').hide();
			$('#admin-table').html(data);
			$('#admin-table').DataTable({
				"responsive": true,
				"searching": false,
				"destroy": true,
				"paging": true,
				"ordering": false,
				pageLength: 5,
				lengthMenu: [[5,10,20,-1],[5,10,20,'All']]
			});
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
	getCtrlNumber();
});

//when edit button on brgy clearance is clicked
$(document).on('click', '#btn-edit-ctrl', function(){
	$('#btn-edit-wrapper').remove();
	$('#options-ctrl').append('<span id="btn-update-wrapper" class="ml-3 justify-content-center row"><button class="btn btn-info mr-1" id="btn-update-ctrl">Update <span class="fas fa-check-circle"></span></button> <button class="btn btn-danger ml-1" id="btn-update-cancel">Cancel <span class="fas fa-times-circle"></span></button></span>');
	$('#ctrl-no').attr('readonly', false);
	$('#ctrl-no').focus();
});
//if cancel button is clicked in update brgy clearance ctrl
$(document).on('click', '#btn-update-cancel', function(){
	//load the current control number for brgy clerance
	$.ajax({
		url: 'includes/load_control_number',
		method: 'GET',
		success: function(data){
			$('#ctrl-no').val(data);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
	$('#btn-update-wrapper').remove();
	$('#options-ctrl').append('<span id="btn-edit-wrapper" class="ml-3 justify-content-center row"><button class="btn btn-success" id="btn-edit-ctrl">Edit <span class="fas fa-edit"></span></button></span>');
	$('#ctrl-no').attr('readonly', true);
});
//when update button is clicked
$(document).on('click', '#btn-update-ctrl', function(){
	$.ajax({
		url: 'includes/update_control_number',
		method: 'POST',
		data: { ctrl: $('#ctrl-no').val().trim() },
		beforeSend: function(){
			$('#loader-update').show();
			$('#btn-update-cancel').attr('disabled', true);
			$('#btn-update-ctrl').attr('disabled', true);
		},
		success: function(data){
			if(data == 'SUCCESS_UPDATE'){
				getCtrlNumber();
				$('#loader-update').hide();
				$('#btn-update-cancel').attr('disabled', false);
				$('#btn-update-ctrl').attr('disabled', false);
				$('#btn-update-wrapper').remove();
				$('#options-ctrl').append('<span id="btn-edit-wrapper" class="ml-3 justify-content-center row"><button class="btn btn-success" id="btn-edit-ctrl">Edit <span class="fas fa-edit"></span></button></span>');
				$('#success-update-alert').show('fade');
				setTimeout(function(){
					$('#success-update-alert').hide('fade');
				}, 2000);
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data);
				$('#loader-update').hide();
				$('#btn-update-cancel').attr('disabled', false);
				$('#btn-update-ctrl').attr('disabled', false);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});