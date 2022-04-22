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

//function for get processing request and add to table
function getTableData(){
	$.ajax({
		url: 'includes/load_processing_request',
		method: 'GET',
		success: function(data){
			if($.fn.DataTable.isDataTable($('#process-table'))){
				$('#proces-table').DataTable().destroy();
			}
			$('#process-table').html(data);
			$('#process-table').DataTable({
				"responsive" : true,
				"searching" : true,
				"ordering" : true,
				"order" : [[5,'asc']],
				"destroy" : true,
				"autoWidth" : false,
				pageLength : 5,
		   		lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
			}).columns([0,2,6,7]).visible(false);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
}

$(document).ready(function(){
	getTimeAndDate();
	$.ajax({
		url: 'includes/load_processing_request',
		method: 'GET',
		beforeSend: function(){
			$('#loader').show();
		},
		success: function(data){
			$('#loader').hide();
			$('#process-table').html(data);
			$('#process-table').DataTable({
				"responsive" : true,
				"searching" : true,
				"ordering" : false,
				"destroy" : true,
				"autoWidth" : false,
				pageLength : 5,
		   		lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
			}).columns([0,2,6,7]).visible(false);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//generate button when clicked
$(document).on('click', '.generate-pdf', function(){
	let id = $(this).attr('id');
	$('#modal-generate-confirm').data('id', id);
	$('#modal-generate-confirm').modal('show');
});
$('#modal-generate-confirm').on('hidden.bs.modal', function(){
	$('#modal-generate-confirm').data('id', '');
});

$('#generate-btn').on('click', function(){
	$.ajax({
		url: 'includes/update_processing_request_function_admin',
		method: 'POST',
		data: { request_id_pdf: $('#modal-generate-confirm').data('id') },
		beforeSend: function(){
			$('#modal-loading-process').modal('show');
			$('#loader-processing').show();
			$('#modal-generate-confirm').modal('hide');
		},
		success: function(data){
			if(data.includes('SUCCESS_GENERATE_PDF')){
				//table refresh
				getTableData();
				$('#loader-processing').hide();
				$('#success-process-modal').show();

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
				$('#loader-processing').hide();
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

//when send sms/email button is clicked
$(document).on('click', '.send-sms', function(){
	let id = $(this).attr('id');

	$.ajax({
		url: 'includes/update_processing_request_function_admin',
		method: 'POST',
		data: { request_id_sms: id },
		beforeSend: function(){
			$('#loader').show();
			$('.send-sms').attr('disabled', true);
			$('.generate-pdf').attr('disabled', true);
		},
		success: function(data){
			if(data == 'SUCCESS_SEND' || data == 'SMS_ONLY_SENT' || data == 'EMAIL_ONLY_SENT'){
				$('#success-process-toast').toast('show');
				$('#loader').hide();
				$('.send-sms').attr('disabled', false);
				$('.generate-pdf').attr('disabled', false);
				$('#process-table').DataTable().row('#'+id).remove().draw();
			}
			else {
				alert('Oops, an error occurred.');
				console.error(data);
				$('#loader').hide();
				$('.send-sms').attr('disabled', false);
				$('.generate-pdf').attr('disabled', false);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});