<?php 
require_once 'database.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$msg = json_decode(file_get_contents("php://input"));
extract($msg);

$conn = connection();

$sql = "{ call pcreatemessage ( ?, ?, ?, ?, ? )}";
$params = array (
		array( $msg_no, SQLSRV_PARAM_IN ),
		array( $msg_src, SQLSRV_PARAM_IN ),
		array( $msg_type, SQLSRV_PARAM_IN ),
		array( $msg_timestamp, SQLSRV_PARAM_IN )
);

$cur = sqlsrv_query($conn, $sql, $params);
sqlsrv_next_result($cur);

$sql = "{call paddmsgmeta ( ?, ?, ?, ? )}";

$kind = '';
$value = '';

$params = array ( $msg_no, $msg_src, &$kind, &$value );

$stmt = sqlsrv_prepare( $conn, $sql, $params );
if( !$stmt ) {
	die( print_r( sqlsrv_errors(), true));
}

foreach ($msg_meta as $kind=>$value) {
	if( sqlsrv_execute( $stmt ) === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
}

$msg_status = '';
$sql = "{call pprocess_one_message ( ?, ?, ? )}";
$params = array( 
		array($msg_no, SQLSRV_PARAM_IN),
		array($msg_src, SQLSRV_PARAM_IN),
		array($msg_status, SQLSRV_PARAM_OUT)
);

sqlsrv_query($conn, $sql, $params);
sqlsrv_next_result($cur);

?>