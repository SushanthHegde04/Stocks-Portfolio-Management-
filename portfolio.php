<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="portfoliostyle.css">

</head>
<body>
    <header>
    <?php
   
   include_once 'functions.php';

    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
// Database connection parameters
$hostname = "localhost";
$username = "root";
$password = "sush@2003";
$database = "Stockguru"; 
// Connect to the database
$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
    
}


if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
  
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'] ;

    $sql1 = "SELECT * FROM Users WHERE UName = '$username' and Email = '$email' and Password='$password'";
    $result = $mysqli->query($sql1);

    if ($result->num_rows > 0){

        while ($row1 = $result->fetch_assoc()) {
            displayUserInfo($row1);
 echo '<div class="seccon">';
        displayAllocation();
        displayBalance($row1["UserID"]); 
        displayInvestedStocks($row1["UserID"]);
        echo '</div>';
        displayStocks($mysqli, $row1);
        }
    }
    }

    ?>
    </header>
    <script src="portscript.js"></script>
</body>
</html>