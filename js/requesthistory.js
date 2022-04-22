//when logout button is clicked
$('#logout-btn').on('click', function(){
	$.ajax({
		url: 'includes/logout',
		success: function(data){
			if(data == 'LOGOUT'){
				window.location.href = 'index';
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

//function for getting the current data from the database
function getTableData(){
	$.ajax({
		url: 'includes/load_request_history',
		method: 'GET',
		success: function(data){
			if($.fn.DataTable.isDataTable("#request-table")) {
				$('#request-table').DataTable().destroy();
			}
			$('#request-table').html(data);
			$('#request-table').DataTable({
				"responsive" : true,
				"searching" : false,
				"ordering" : true,
				"order" : [[ 1, 'desc']],
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

//load table data on request history
$(document).ready(function(){
	$.ajax({
		url: 'includes/load_request_history',
		method: 'GET',
		beforeSend: function(){
			$('#loader').show();
		},
		success: function(data){
			$('#loader').hide();
			$('#request-table').html(data);
			$('#request-table').DataTable({
				"responsive" : true,
				"searching" : false,
				"ordering" : true,
				"order" : [[ 1, 'desc']],
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
});

//set modal data
$(document).on('click', '.delete-request', function(){
	let id = $(this).attr('id');
	$('#modal-delete-request').data('id', id);
	$('#modal-delete-request').modal('show');
});

//when modal delete is closed
$('#modal-delete-request').on('hidden.bs.modal', function(){
	$('#modal-delete-request').data('id', "");
});

//when Yes is clicked on delete request
$('#confirm-cancel-request').on('click', function(){
	$.ajax({
		url: 'includes/cancel_request_function',
		method: 'POST',
		data: { requestNo: $('#modal-delete-request').data('id') },
		async: false,
		beforeSend: function(){
			$('#loader-delete').show();
			$('#no-btn').attr('disabled', true);
			$('#confirm-cancel-request').attr('disabled', true);
		},
		success: function(data){
			if(data == 'SUCCESS_CANCEL_REQ'){
				$('#loader-delete').hide();
				//table reload
				getTableData();
				$('#no-btn').attr('disabled', false);
				$('#confirm-cancel-request').attr('disabled', false);
				$('#modal-delete-request').data('id', "");
				$('#modal-delete-request').modal('hide');
				$('#modal-success-delete').modal('show');
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data);
				$('#loader-delete').hide();
				$('#no-btn').attr('disabled', false);
				$('#confirm-cancel-request').attr('disabled', false);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});