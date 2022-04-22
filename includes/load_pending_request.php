<?php 
	require 'load_table_requests_admin.php';

	//get all pending request and add to table (Admin)
	$getPending = new Functions();
	echo $getPending->loadRequest('PENDING');
	unset($getPending);
?>