<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Creating Array for JSON response
$response = array();
 
// Check if we got the field from the user
if (isset($_GET['eventId']) && isset($_GET['count'])) {
 
    $eventId = $_GET['eventId'];
    $count= $_GET['count'];
 
    // Include data base connect class
	$filepath = realpath (dirname(__FILE__));
	require_once($filepath."/db_connect.php");

	// Connecting to database
    $db = new DB_CONNECT();
    $con = $db->get_connection();
 
	// Fire SQL query to update PeopleCounter data by eventId
    $result = mysqli_query($con, "UPDATE PeopleCounter SET count = '$count' WHERE eventId = '$eventId'");
 
    // Check for successful execution of query and no results found
    if ($result) {
        // successfully updation of count (count)
        $response["success"] = 1;
        $response["message"] = "PeopleCounter Data successfully updated.";
 
        // Show JSON response
        echo json_encode($response);
    } else {
 
    }
} else {
    // If required parameter is missing
    $response["success"] = 0;
    $response["message"] = "Parameter(s) are missing. Please check the request";
 
    // Show JSON response
    echo json_encode($response);
}
?>