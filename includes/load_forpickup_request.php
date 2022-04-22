<?php 
	require 'load_table_requests_admin.php';

	//get all for pickup request and add to table (Admin)
	$getForPickup = new Functions();
	echo $getForPickup->loadRequest('FOR PICKUP');
	unset($getForPickup);
?>