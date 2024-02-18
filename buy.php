<?php
$hostname = "localhost";
$username = "";
$password = "";
$database = "Stockguru";
include_once 'functions.php';
$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["action"])) {
        $action = $_POST["action"];
            echo $action;
        if ($action === "buy") {
            if (isset($_POST["stockId"])) {
                $userId = $_POST["userId"];
                $stockId = $_POST["stockId"];

                // Checking if the quantity form was submitted
                if (isset($_POST["quantity"])) {
                    $quantity = $_POST["quantity"];

                    $checkStmt = $mysqli->prepare("SELECT Quantity FROM Transactions WHERE UserID = ? AND StockID = ? and TransactionType='Buy'");
                    $checkStmt->bind_param("ii", $userId, $stockId);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();
                    $checkRow = $checkResult->fetch_assoc();
                    $checkStmt->close();

                    if ($checkRow) {
                        // User already has the stock, update the quantity
                        $updateStmt = $mysqli->prepare("UPDATE Transactions SET Quantity = Quantity + ? WHERE UserID = ? AND StockID = ? and TransactionType='Buy'");
                        $updateStmt->bind_param("iii", $quantity, $userId, $stockId);
                        $updateStmt->execute();

                        
                            // Update successful
                            displayInvestedStocks($userId);
                            displayBalance($userId);
                            echo "Transaction quantity updated successfully.";
                        

                        $updateStmt->close();
                    } else {
                        // User doesn't have the stock, insert a new transaction
                        $insertStmt = $mysqli->prepare("INSERT INTO Transactions (UserID, StockID, TransactionType, Quantity, PricePerShare, TransactionDate)
                            VALUES (?, ?, 'Buy', ?, (SELECT CurrentPrice FROM Stocks WHERE StockID = ?), CURRENT_DATE())");
                        $insertStmt->bind_param("iiii", $userId, $stockId, $quantity, $stockId);
                        $insertStmt->execute();

                        if ($insertStmt->affected_rows > 0) {
                            // Insert successful
                            displayInvestedStocks($userId);
                            displayBalance($userId);
                            echo "Transaction added successfully.";
                        } else {
                            echo "Error adding transaction.";
                        }

                        $insertStmt->close();
                    }
                } else {
                    // Display the quantity input form
                    echo "Error: Missing quantity.";
                }
            } else {
                echo "Error: Missing stock ID.";
            }
        } else if ($action === "sell") {
            if (isset($_POST["stockId"])) {
                $userId = $_POST["userId"];
                $stockId = $_POST["stockId"];
        
                if (isset($_POST["quantity"])) {
                    $quantityToSell = $_POST["quantity"];
        
                    // Check if the user has enough quantity to sell
                    $checkStmt = $mysqli->prepare("SELECT Quantity FROM Transactions WHERE UserID = ? AND StockID = ?");
                    $checkStmt->bind_param("ii", $userId, $stockId);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();
                    $checkRow = $checkResult->fetch_assoc();
                    $checkStmt->close();
        
                    if ($checkRow && $checkRow["Quantity"] >= $quantityToSell) {
                        // User has enough quantity, update the transaction
                        $updateStmt = $mysqli->prepare("UPDATE Transactions SET Quantity = Quantity - ? WHERE UserID = ? AND StockID = ? and TransactionType='Buy'");
                        $updateStmt->bind_param("iii", $quantityToSell, $userId, $stockId);
                        $updateStmt->execute();
                        
                        $insertstmt1 = $mysqli->prepare("INSERT INTO Transactions (UserID, StockID, TransactionType, Quantity, PricePerShare, TransactionDate)
                        VALUES (?, ?, 'Sell', ?, (SELECT CurrentPrice FROM Stocks WHERE StockID = ?), CURRENT_DATE()) ");
                         $insertstmt1->bind_param("iiii", $userId, $stockId, $quantityToSell , $stockId);
                         $insertstmt1->execute();
                          // Check if the remaining quantity is 0, then delete the tuple
                            $checkRemainingStmt = $mysqli->prepare("SELECT Quantity FROM Transactions WHERE UserID = ? AND StockID = ? and TransactionType='Buy' ");
                            $checkRemainingStmt->bind_param("ii", $userId, $stockId);
                            $checkRemainingStmt->execute();
                            $remainingResult = $checkRemainingStmt->get_result();
                            $remainingRow = $remainingResult->fetch_assoc();
                            $checkRemainingStmt->close();
        
                            if ($remainingRow["Quantity"] == 0) {
                                $deleteStmt = $mysqli->prepare("DELETE FROM Transactions WHERE UserID = ? AND StockID = ?");
                                $deleteStmt->bind_param("ii", $userId, $stockId);
                                $deleteStmt->execute();
        
                                
        
                                $deleteStmt->close();
                            }
                        
        
                        $updateStmt->close();
                        displayInvestedStocks($userId);
                            displayBalance($userId);
                    } else {
                        echo "Error: Insufficient quantity to sell.";
                    }
                } else {
                    // Display the quantity input form
                    echo "Error: Missing quantity.";
                }
            } else {
                echo "Error: Missing stock ID.";
            }
        }
        
    } else {
        echo "Error: Missing action.";
    }
} 


else {
    echo "Error: Invalid request.";
}

// Close the database connection
$mysqli->close();
?>
