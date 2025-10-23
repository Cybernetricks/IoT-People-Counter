<?php

class DB_CONNECT {
    var mysqli $con;

    // Constructor
    function __construct() {
        // Trying to connect to the database
        $this->con = $this->connect();
    }
 
    // Destructor
    function __destruct() {
        // Closing the connection to database
        $this->close();
    }
 
   // Function to connect to the database
    function connect() {
        $error = '';

        //importing dbconfig.php file which contains database credentials 
        $filepath = realpath (dirname(__FILE__));

        require_once($filepath."/dbconfig.php");
        
		// Connecting to mysql database
        $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die("could not connect");
        
        if (mysqli_connect_errno()) {
            return mysqli_connect_errno();
        }

        // Selecting database
        $db = mysqli_select_db($connection, DB_DATABASE);

        if (mysqli_connect_errno()) {
            return mysqli_connect_errno();
        }
 
        // returing connection cursor
        return $connection;
    }
 
	// Function to close the database connection
    function close() {
        // Closing data base connection
        mysqli_close($this->con);
    }

    // Function to get the connection
    function get_connection(){
        return $this->con;
    }
}

?>