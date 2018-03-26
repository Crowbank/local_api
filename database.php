<?php 
function connection() {
	$serverName = "(local)\sqlexpress";
	$connectionOptions = array("Database" => "crowbank",
			"UID" => "PA",
			"PWD" => "petadmin"
	);
	
	/* Connect using Windows Authentication. */
	$conn = sqlsrv_connect( $serverName, $connectionOptions);
	return $conn;
}
?>