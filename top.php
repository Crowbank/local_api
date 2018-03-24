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
	
	$cur = sqlsrv_query($conn, 'Select emp_no, emp_nickname from tblemployee where emp_iscurrent=1');
	
	while( $obj = sqlsrv_fetch_object( $cur)) {
		echo $obj->emp_no . ": " . $obj->emp_nicKname . "<br />";
	}
}

function try_odbc() {
	/* $dbh = new PDO('Provider=SQLNCLI11;Server=sqlexpress;Database=crowbank;Uid=PA;Pwd=petadmin;'); */
	$user = 'crowbank';
	$pass = 'Crowbank454';
	$dbh = new PDO('SQLSRV:host=localhost;dbname=crowbank_petadmin', $user, $pass);
	
	foreach($dbh->query('SELECT emp_no, emp_nickname from my_employee where emp_iscurrent=1') as $row) {
		print_r($row);
	}
}

function try_mysql() {
	$dsn = 'mysql: host=localhost;dbname=crowbank_petadmin';
	$dsn = 'Provider=MSDASQL;Driver={MySQL ODBC 5.2w Driver};Server=localhost;Database=crowbank_petadmin;User=crowbank;Password=Crowbank454;Option=3';
	$dsn = 'Driver={mySQL};Server=localhost;Option=16834;Database=crowbank_petadmin;';
	$dsn = 'Driver={MySQL ODBC 5.2w Driver};Server=localhost;Database=crowbank_petadmin;User=crowbank;Password=Crowbank454;Option=3';
	$dsn = 'Driver=MySQL;Server=localhost;Database=crowbank_petadmin;User=crowbank;Password=Crowbank454;';
	$username = 'crowbank';
	$password = 'Crowbank454';
	$options = array(
/*			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', */
	);

	$dbh = new PDO($dsn);
	print_r($dbh);
}

function try_pdo() {
	$serverName = "sqlexpress";
	
	/* Connect using Windows Authentication. */
	try
	{
		$conn = new PDO( "sqlsrv:server=$serverName ; Database=crowbank", "PA", "petadmin");
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		
		echo 'Made connection\n';
	}
	catch(Exception $e)
	{
		echo 'Failed\n';
		die( print_r( $e->getMessage() ) );
	}  
	
	$tsql = "SELECT emp_no, emp_nickname from tblemployee where emp_iscurrent=1";
	
	$s = $conn->prepare($tsql);
	$s->execute();
	$emps = $s->fetchAll(PDO::FETCH_ASSOC);
	$emp_count = count($emps);
	if($emp_count > 0)
	{
		foreach( $emps as $row ) {
			$emp_no = $row['emp_no'];
			$nickname = $row['emp_nickname'];
			
			echo sprintf('%d: %s\n', $emp_no, $nickname);
		}
	}

}

try_sqlsrv();
