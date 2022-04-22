if($('#nav-1').hasClass('active-state'))
	$('#nav-1').removeClass('active-state');
if($('#nav-2').hasClass('active-state'))
	$('#nav-2').removeClass('active-state');

$('#nav-3').addClass('active-state');

//custom date picker
$(function(){
	const yearToday = new Date();
	const startDate = 2021;
	$('#start-date-field').datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: '-' + (yearToday.getFullYear() - startDate).toString() + 'Y' + ':+0Y',
		dateFormat: 'yy-mm-dd'
	});
	$('#end-date-field').datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: '-' + (yearToday.getFullYear() - startDate).toString() + 'Y' + ':+0Y',
		dateFormat: 'yy-mm-dd'
	});
});

//remedy for datepicker sticking
$('#wrapper').scroll(function(){
    $("#start-date-field").datepicker("hide");
    $("#start-date-field").blur();
    $("#end-date-field").datepicker("hide");
    $("#end-date-field").blur();
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

//resident records check
$(document).on('click', '#check-resident-record-btn', function(){
	$.ajax({
		url: 'includes/check-resident-records',
		method: 'GET',
		beforeSend: function(){
			$('#spinner-check-records').show();
			$('#check-resident-record-btn').attr('disabled', true);
		},
		success: function(data){
			const json = JSON.parse(data);

			if(json.record_count == 0){
				$('#alert-resident-record').html('There are ' + json.record_count + ' record(s) of verified residents found in total');
				$('#alert-resident-record').show();
				$('#spinner-check-records').hide();
				$('#check-resident-record-btn').attr('disabled', false);
			}
			else if(json.record_count > 0){
				$('#alert-resident-record').html('There are ' + json.record_count + ' record(s) of verified residents found in total');
				$('#alert-resident-record').show();
				$('#button-holder').html('<button class="btn btn-info my-3" id="generate-resident-report-btn">Generate Report <div class="spinner-border spinner-border-sm text-light" id="spinner-generate-resident" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>');
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

//generate reports on resident records
$(document).on('click', '#generate-resident-report-btn', function(){
	let opt = {
		margin:       0.5,
	  	filename:     'list_of_residents_report.pdf',
	  	image:        { type: 'jpeg', quality: 1 },
	  	html2canvas:  { scale: 2, dpi: 600, letterRendering: true, useCORS: true },
	  	jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' },
	  	pagebreak: 	{ mode: ['css', 'legacy'] }
	};
	$.ajax({
		url: 'includes/generate-resident-list-report',
		method: 'GET',
		beforeSend: function(){
			$('#generate-resident-report-btn').attr('disabled', true);
			$('#spinner-generate-resident').show();
		},
		success: function(data){
			html2pdf()
			.set(opt)
			.from(data)
			.save();
			$('#button-holder').html('<button class="btn btn-success my-3" id="check-resident-record-btn">Check Records <div class="spinner-border spinner-border-sm text-light" id="spinner-check-records" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>');
			$('#alert-resident-record').hide();
			$('#alert-resident-record').html('');
			$('#modal-success-generate').modal('show');
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

$('#start-date-field').on('change', function(){
	$('#start-date-field').datepicker('hide');
	$('#start-date-field').blur();
	if($('#start-date-field').hasClass('is-invalid') || $('#end-date-field').hasClass('is-invalid')){
		$('#start-date-field').removeClass('is-invalid');
		$('#end-date-field').removeClass('is-invalid');
		error_count--;
	}
});
$('#end-date-field').on('change', function(){
	$('#end-date-field').datepicker('hide');
	$('#end-date-field').blur();
	if($('#end-date-field').hasClass('is-invalid') || $('#start-date-field').hasClass('is-invalid')){
		$('#end-date-field').removeClass('is-invalid');
		$('#start-date-field').removeClass('is-invalid');
		error_count--;
	}
});

//transaction records check
$(document).on('click', '#check-transact-record-btn', function(){
	let startDate = $('#start-date-field').val();
	let endDate = $('#end-date-field').val();
	const checkStart = new Date(startDate);
	const checkEnd = new Date(endDate);
	let error_count = 0;

	if(startDate == ''){
		$('#start-date-error').html('Please input start date');
		$('#start-date-field').addClass('is-invalid');
		error_count++;
	}
	else if(startDate != ''){
		if(checkStart.getTime() > checkEnd.getTime()){
			$('#start-date-error').html('Start date should be smaller than end date');
			$('#start-date-field').addClass('is-invalid');
			error_count++;
		}
	}

	if(endDate == ''){
		$('#end-date-error').html('Please input end date');
		$('#end-date-field').addClass('is-invalid');
		error_count++;
	}
	else if(endDate != ''){
		if(checkEnd.getTime() < checkStart.getTime()){
			$('#end-date-error').html('End date should be larger than start date');
			$('#end-date-field').addClass('is-invalid');
			error_count++;
		}
	}

	//proceed if there is no error
	if(error_count == 0){
		$.ajax({
			url: 'includes/check-transact-records',
			method: 'POST',
			data: { start: startDate, end: endDate},
			beforeSend: function(){
				$('#spinner-check-transact').show();
				$('#check-transact-record-btn').attr('disabled', true);
			},
			success: function(data){
				const json1 = JSON.parse(data);

				if(json1.transaction_count == 0){
					$('#alert-transact-record').html('There are ' + json1.transaction_count + ' completed transactions from ' + startDate + ' to ' + endDate);
					$('#alert-transact-record').show();
					$('#spinner-check-transact').hide();
					$('#check-transact-record-btn').attr('disabled', false);
				}
				else if(json1.transaction_count > 0){
					$('#alert-transact-record').html('There are ' + json1.transaction_count + ' completed transactions from ' + startDate + ' to ' + endDate);
					$('#alert-transact-record').show();
					$('#button-holder-transact').html('<button class="btn btn-info my-3" id="generate-transact-report-btn">Generate Report <div class="spinner-border spinner-border-sm text-light" id="spinner-generate-transact" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>');
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
	}
});

//generate reports on transaction records
$(document).on('click', '#generate-transact-report-btn', function(){
	let startDate = $('#start-date-field').val();
	let endDate = $('#end-date-field').val();

	$.ajax({
		url: 'includes/generate-completed-transaction-report',
		method: 'POST',
		data: { start: startDate, end: endDate },
		beforeSend: function(){
			$('#generate-transact-report-btn').attr('disabled', true);
			$('#spinner-generate-transact').show();
		},
		success: function(data){
			let opt = {
				margin:       0.5,
			  	filename:     'completed-transaction-report.pdf',
			  	image:        { type: 'jpeg', quality: 1 },
			  	html2canvas:  { scale: 2, dpi: 600, letterRendering: true, useCORS: true },
			  	jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' },
			  	pagebreak: 	{ mode: ['css', 'legacy'] }
			};

			html2pdf()
			.set(opt)
			.from(data)
			.save();
			$('#button-holder-transact').html('<button class="btn btn-success my-3" id="check-transact-record-btn">Check Records <div class="spinner-border spinner-border-sm text-light" id="spinner-check-transact" role="status" style="display: none;"><span class="sr-only">Loading...</span></div></button>');
			$('#alert-transact-record').hide();
			$('#alert-transact-record').html('');
			$('#modal-success-generate').modal('show');
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});