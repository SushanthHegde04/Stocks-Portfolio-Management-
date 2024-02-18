<?php
$hostname = "localhost";
$username = "";
$password = "";
$database = "Stockguru"; 
// Connect to the database
$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function displayUserInfo($row) {
    echo '<div class="container">';
    echo ' <div class="user"> ';
    echo "STOCK_MASTER";
    echo '<ul >';
    echo '<img src="user.jpg" alt="userimg">';
    echo '<li> '. $row["UserID"] . '</li>';
    echo '<li> ' . $row["UName"] .' </li>';
    echo '<li> '. $row["Email"] . '</li>';

    echo '<li><button class="delete-account-button" onclick=" delccount(' .$row["UserID"]. ')">Delete Account</button></li>';
    echo '</ul>';
    echo '</div>';
}
echo "
<script>

function delccount(UserId) {
    window.location.href = 're.html';
    alert('ARE YOU SURE!!!!'); 

    fetch('delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'userId=' + userId,
    })
    .then(response => {
        if (response.ok) {
            alert('Delete Successful'); // Alert for successful deletion
            // Redirect to re.html after successful deletion
        } else {
            alert('Failed to delete account'); // Alert for failed deletion
            window.location.href = 're.html'; // Redirect to re.html even if deletion fails
        }
    })
    .catch(error => {
        console.error('Error deleting account:', error);
        alert('Error deleting account'); // Alert for any error during deletion
    });
}

</script>
";
function displayAllocation() {
    echo '<div class="Allocation" style="margin: 10px;">';
    echo "<br>";
    echo "Allocated Money";
   
    echo "<br>";
    echo "<br>";
    echo "Rs1000000"  ;

    echo "<br>";
    echo "<br>";
    echo '</div>';
}

function displayBalance($UserID) {
    global $mysqli; // Add this line to use the global variable

    $sql1 = "SELECT * FROM Users WHERE UserID = '$UserID' ";
    $result = $mysqli->query($sql1);
    $row = $result->fetch_assoc();
    echo '<div class="balance"';
    echo "<br>";
    echo "<br>";
    echo "BALANCE:";
    echo "<br>";
    echo "<br>";
    echo $row["Available"];
    echo "<br>"; 
    echo "<br>";
    echo "<i></i>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "Invested:";
    echo "<br>";
    echo "<br>";
    echo $row["Invested"];
    echo "<br>"; 
    echo "<br>";
    echo '</div>';
}

function displayInvestedStocks($userID) {
    global $mysqli; 
    echo '<div class="INVESTED">';
    echo "<br>";

    $sql = "SELECT
        ons.Stockid,
        st.StockSymbol,
        st.CurrentPrice,
        ons.Quantity
    FROM
        Users u
    JOIN
    Transactions ons ON u.UserId = ons.UserId
    JOIN
        Stocks st ON ons.StockId = st.StockId
    
    WHERE
        u.UserId = '$userID'
        and ons.TransactionType='Buy'
    ";

    $result = $mysqli->query($sql);

    if ($result === false || $result->num_rows == 0) 
        echo "NOT YET PURCHASED ANY STOCKS DUDE, DO IT FAST!";
    else {
        echo' <table>';
        echo '<tr>';
        echo '<th>Stock ID</th>';
        echo '<th>Stock Symbol</th>';
        echo ' <th>Current Price</th>';
        echo ' <th>Quantity</th>';
        echo '<th>Action</th>';

        echo '</tr>';

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Stockid"] . "</td>";
            echo "<td>" . $row["StockSymbol"] . "</td>";
            echo "<td>" . $row["CurrentPrice"] . "</td>";
            echo "<td>" . $row["Quantity"] . "</td>";
            echo "<td><button class='sell-button' onclick='openNewPage(this)' data-stock-id='" . $row["Stockid"] . "' data-user-id='$userID' data-type='sell'>Sell</button></td>";
            echo "</tr>";
        }
        
        echo '</table>';
    }

    echo '</div>';
}

function displayStocks($mysqli, $row1) {
    echo '<div class="stocks">';
    $sql = "SELECT StockId, CompanyID, StockSymbol, CurrentPrice, MarketCap, DividendYield FROM Stocks";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr>
            <th>StockId</th>
            <th>CompanyID</th>
            <th>StockSymbol</th>
            <th>MarketCap</th>
            <th>DividendYield</th> 
            <th>CurrentPrice</th>
            <th>Action</th>
        </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["StockId"] . "</td>
                <td>" . $row["CompanyID"] . "</td>
                <td>" . $row["StockSymbol"] . "</td>
                <td>" . $row["MarketCap"] . "</td>
                <td>" . $row["DividendYield"] . "</td>
                <td>" . $row["CurrentPrice"] . "</td>
                <td><button class='add-button' onclick='openNewPage(this)' data-stock-id='" . $row["StockId"] . "' data-user-id='" . $row1["UserID"] . "' data-type='buy'>Add</button></td>

                </tr>";
        }
        echo "</table>";
    }

    echo '</div>';
}
?>
