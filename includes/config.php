<?php 

	//output buffering, wait for all data before sending it to the server
	ob_start(); 

	//enable the use of sessions, this will keep users logged in
	session_start();

	//Set the timezone of the server
	$timezone = date_default_timezone_set("America/New_York");

	//connect to the database:
	$con = mysqli_connect("localhost", "root", "", "slotify"); //host, user, pass, dbname

	//if no connection, spit error
	if(mysqli_connect_errno()) {
		echo "Failed to connect: " . mysqli_connect_errno();
	}
?>