if($('#nav-1').hasClass('active-state'))
	$('#nav-1').removeClass('active-state');
if($('#nav-2').hasClass('active-state'))
	$('#nav-2').removeClass('active-state');
if($('#nav-3').hasClass('active-state'))
	$('#nav-3').removeClass('active-state');

$('#nav-4').addClass('active-state');

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

$(document).ready(function(){
    $.ajax({
        url: 'includes/load_logs',
        method: 'GET',
        beforeSend: function(){
            $('#loader').show();
        },
        success: function(data){
            $('#loader').hide();

            const tableData = JSON.parse(data);
            tableData.forEach(function(row){
                if(row.status === 'COMPLETED'){
                    const tableRow = document.createElement('tr');
                
                    let tableRowData = document.createElement('td');
                    tableRowData.textContent = row.request_id;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.name_of_requestor;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.status;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.request_type;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.purpose;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.date_completed;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.admin;
                    tableRow.append(tableRowData);

                    $('#table-data').append(tableRow);
                }
                else if(row.status === 'CANCELLED'){
                    const tableRow = document.createElement('tr');
                
                    let tableRowData = document.createElement('td');
                    tableRowData.textContent = row.request_id;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.name_of_requestor;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.status;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.request_type;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.purpose;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.date_requested;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.admin;
                    tableRow.append(tableRowData);

                    $('#table-data2').append(tableRow);
                }
                else if(row.status === 'DECLINED'){
                    const tableRow = document.createElement('tr');
                
                    let tableRowData = document.createElement('td');
                    tableRowData.textContent = row.request_id;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.name_of_requestor;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.status;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.request_type;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.purpose;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.date_requested;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.remarks;
                    tableRow.append(tableRowData);
                    tableRowData = document.createElement('td');
                    tableRowData.textContent = row.admin;
                    tableRow.append(tableRowData);

                    $('#table-data3').append(tableRow);
                }
            });

            $('#completed-table').DataTable({
                "responsive" : true,
				"searching" : true,
				"ordering" : true,
				"autoWidth" : false,
                "order" :  [[2,'asc']],
				pageLength : 5,
		   		lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
            }).columns([0]).visible(false);

            $('#cancelled-table').DataTable({
                "responsive" : true,
				"searching" : true,
				"ordering" : true,
				"autoWidth" : false,
                "order" :  [[2,'asc']],
				pageLength : 5,
		   		lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
            }).columns([0]).visible(false);

            $('#declined-table').DataTable({
                "responsive" : true,
				"searching" : true,
				"ordering" : true,
				"autoWidth" : false,
                "order" :  [[2,'asc']],
				pageLength : 5,
		   		lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
            }).columns([0]).visible(false);
        },
        error: function(xhr){
            alert('Oops, an error occurred.');
            console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
        }
    });
});