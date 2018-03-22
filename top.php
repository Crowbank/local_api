<?php 
echo 'Welcome to the Crowbank Local Listener';

function OpenConnection()
{
	try
	{
		$serverName = "tcp:myserver.database.windows.net,1433";
		$connectionOptions = array("Database"=>"AdventureWorks",
				"Uid"=>"MyUser", "PWD"=>"MyPassword");
		$conn = sqlsrv_connect($serverName, $connectionOptions);
		if($conn == false)
			die(FormatErrors(sqlsrv_errors()));
	}
	catch(Exception $e)
	{
		echo("Error!");
	}
}

OpenConnection();
