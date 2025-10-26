<?php
 
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");

//Creating Array for JSON response
$response = array();

// Check if we got the eventId field from the user
if (isset($_GET['eventId'])) {
    $eventId = $_GET['eventId'];
 
    // Include data base connect class
    $filepath = realpath (dirname(__FILE__));
	require_once($filepath."/db_connect.php");
 
    // Connecting to database 
    $db = new DB_CONNECT();
    $con = $db->get_connection();
 
    // Fire SQL query to delete peoplecounter data by id
    $result = mysqli_query($con, "DELETE FROM PeopleCounter WHERE eventId = '$eventId';");
 
    // Check for successful execution of query
    if (mysqli_affected_rows($con) > 0) {
        // successfully deleted
        $response["success"] = 1;
        $response["message"] = "Data successfully deleted";
 
        // Show JSON response
        echo json_encode($response);
    } else {
        // no matched id found
        $response["success"] = 0;
        $response["message"] = "No PeopleCounter data found by given eventId";
 
        // Echo the failed response
        echo json_encode($response);
    }
} else {
    // If required parameter is missing
    $response["success"] = 0;
    $response["message"] = "Parameter(s) are missing. Please check the request";
 
    // Show JSON response
    echo json_encode($response);
}
?>