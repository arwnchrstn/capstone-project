//when logout button is clicked
$('#logout-btn').on('click', function(){
	$.ajax({
		url: 'includes/logout',
		success: function(data){
			if(data == 'LOGOUT'){
				window.location.href = 'index';
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});