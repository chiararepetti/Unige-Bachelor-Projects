<?php
include 'db/connect_to_db.php';

$cartid = $_SESSION['cart'];

$query = "SELECT * FROM cart_details JOIN records ON cart_details.recordid = records.recordid WHERE cart_details.cartid=?;";

$stmt = $conn->prepare($query);

if (!$stmt) {
error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
exit("Something went wrong... try again later.");
}

if (!($stmt->bind_param("i", $cartid))) {
    error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}

if (!($stmt->execute())) {
error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
exit("Something went wrong... try again later.");
}

$result = $stmt->get_result();

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>