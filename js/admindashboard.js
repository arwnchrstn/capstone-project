$('.card-footer').hover(function(){
	$(this).find('a').attr('style', 'color: white!important; font-weight: 500;');
    }, function(){
    $(this).find('a').attr('style', 'color: #28a745!important; font-weight: 500;');
});
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
$(document).ready(function(){
	getTimeAndDate();
	getPendingCount();
	getProcessingCount();
	getForPickupCount();
	getCompletedCount();
	getNonVerifiedCount();
	getVerifiedCount();
	getTotalRegistered();
});

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

//get pending request count
setInterval(getPendingCount, 5000);
function getPendingCount(){
	$.ajax({
		url: 'includes/load_number_of_pending',
		method: 'GET',
		success: function(data){
			$('#pending-count').html(data);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

//get processing request count
function getProcessingCount(){
	$.ajax({
		url: 'includes/load_number_of_processing',
		method: 'GET',
		success: function(data){
			$('#processing-count').html(data);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

//get processing request count
function getForPickupCount(){
	$.ajax({
		url: 'includes/load_number_of_forpickup',
		method: 'GET',
		success: function(data){
			$('#pickup-count').html(data);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

//get processing request count
function getCompletedCount(){
	$.ajax({
		url: 'includes/load_number_of_completed',
		method: 'GET',
		success: function(data){
			$('#completed-count').html(data);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

//get non verified users count
setInterval(getNonVerifiedCount, 5000);
function getNonVerifiedCount(){
	$.ajax({
		url: 'includes/load_number_of_nonverified_user',
		method: 'GET',
		success: function(data){
			$('#non-verified-user').html(data);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

setInterval(getVerifiedCount, 5000);
function getVerifiedCount(){
	$.ajax({
		url: 'includes/load_number_of_verified_user',
		method: 'GET',
		success: function(data){
			$('#verified-user').html(data);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

setInterval(getTotalRegistered, 5000);
function getTotalRegistered(){
	$.ajax({
		url: 'includes/load_number_of_total_registered',
		method: 'GET',
		success: function(data){
			$('#total-registered-user').html(data);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}