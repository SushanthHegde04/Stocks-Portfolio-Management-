<?php

if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Connect to the database
    $hostname = "localhost";
    $username = "";
    $password = "";
    $database = "Stockguru";

    // Create connection
    $mysqli = new mysqli($hostname, $username, $password, $database);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    //xecute the stored procedure
    $query = "CALL DeleteUserWithTransactions($userId)";
    if ($mysqli->query($query) === TRUE) {
    
        echo "User with ID: " . $userId . " deleted successfully";
    } else {
        
        
        echo "Error deleting user: " . $mysqli->error;
       
    }

    // Close the database connection
    $mysqli->close();

  
} else {
    echo "No user ID received!";
}
?>
