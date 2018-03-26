<?php 
echo 'Welcome to the Crowbank Local Listener<br>';

function try_sqlsrv() {
	$serverName = "(local)\sqlexpress";
	$connectionOptions = array("Database" => "crowbank",
			"UID" => "PA",
			"PWD" => "petadmin"
	);
	
	/* Connect using Windows Authentication. */
	$conn = sqlsrv_connect( $serverName, $connectionOptions);
	if( $conn === false )
		die( print_r( sqlsrv_errors() ) );

	$sql = "{ call ptest ( ?, ? )}";
	$n = 0;
	$val = 'abc';
	$params = array (
			array( $val, SQLSRV_PARAM_IN ),
			array( $n, SQLSRV_PARAM_OUT )
	);
	
	$cur = sqlsrv_query($conn, $sql, $params);
	sqlsrv_next_result($cur);
	
	if ($cur) {
		echo 'Call successful<br>';
		echo 'Returned n = ' . $n;
		
		sqlsrv_free_stmt( $cur);
		sqlsrv_close( $conn );		
	} else {
		echo 'Call failed<br>';
	}
	
}

try_sqlsrv();
