<?php
include 'db/connect_to_db.php';

if (isset($_POST['checkout'])) {
    $total = $_POST['checkout'];
    $cartid = $_SESSION['cart'];
    $userid = $_SESSION['id'];

    $query = "UPDATE carts SET total=? WHERE cartid=? AND userid=?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("dii", $total, $cartid, $userid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    header("Location: checkout.php");

}
?>
