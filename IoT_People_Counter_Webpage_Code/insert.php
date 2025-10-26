<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Creating Array for JSON response
$response = array();
 
// Check if we got the count field from the user
if (isset($_GET['count'])) {
    $sensorGroup = $_GET['sensorGroup'];
    $count = $_GET['count'];
    $eventDateStamp = $_GET['eventDateStamp'];
    $eventHourStamp = $_GET['eventHourStamp'];
 
    // Include data base connect class
    $filepath = realpath (dirname(__FILE__));
	require_once($filepath."/db_connect.php");
 
    // Connecting to database 
    $db = new DB_CONNECT();
    $con = $db->get_connection();
    $result;

    // Fire SQL query to check if record at specific time and sensorGroup already exists for increment/decrement
    $check = mysqli_query($con, "SELECT eventId FROM PeopleCounter 
                                 WHERE sensorGroup = '$sensorGroup' && eventDateStamp = '$eventDateStamp' && eventHourStamp = '$eventHourStamp';");
    
    // if condition which either increments/decrements or inserts new data if data exists or not
    // use mysqli_num_rows from read_all.php for condition
    if (mysqli_num_rows($check) > 0) {
        $temporaryEventId = mysqli_fetch_array($check)["eventId"];
        $result = mysqli_query($con, "UPDATE PeopleCounter SET count = count + '$count'
                                      WHERE eventId = '$temporaryEventId';");
    } else if (mysqli_num_rows($check) == 0) {
        // Fire SQL query to insert data in PeopleCounter
        $result = mysqli_query($con, "INSERT INTO PeopleCounter(sensorGroup, count, eventDateStamp, eventHourStamp) 
                                      VALUES('$sensorGroup', '$count', '$eventDateStamp', '$eventHourStamp')");
    }
    
    // Check for successful execution of query
    if ($result) {
        // successfully inserted 
        $response["success"] = 1;
        $response["message"] = "PeopleCounter successfully created.";
 
        // Show JSON response
        echo json_encode($response);
    } else {
        // Failed to insert data in database
        $response["success"] = 0;
        $response["message"] = "Something has been wrong";
 
        // Show JSON response
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