<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


//Creating Array for JSON response
$response = array();
 
// Include data base connect class
$filepath = realpath (dirname(__FILE__));
require_once($filepath."/db_connect.php");

 // Connecting to database 
$db = new DB_CONNECT();	
$con = $db->get_connection();

 // Fire SQL query to get all data from peoplecounter
$result = mysqli_query($con, "SELECT * FROM PeopleCounter") or die(mysqli_error($con));
 
// Check for successful execution of query and no results found
if (mysqli_num_rows($result) > 0) {
    
	// Storing the returned array in response
    $response["peoplecounter"] = array();
 
	// While loop to store all the returned response in variable
    while ($row = mysqli_fetch_array($result)) {
        // temporary user array
        $peopleCounter = array();
        $peopleCounter["eventId"] = $row["eventId"];
        $peopleCounter["count"] = $row["count"];
        $peopleCounter["eventDateStamp"] = $row["eventDateStamp"];
        $peopleCounter["eventHourStamp"] = $row["eventHourStamp"];

		// Push all the items 
        array_push($response["peoplecounter"], $peopleCounter);
    }
    // On success
    $response["success"] = 1;
 
    // Show JSON response
    echo json_encode($response);
}	
else 
{
    // If no data is found
	$response["success"] = 0;
    $response["message"] = "No data on peoplecounter found";
 
    // Show JSON response
    echo json_encode($response);
}
?>