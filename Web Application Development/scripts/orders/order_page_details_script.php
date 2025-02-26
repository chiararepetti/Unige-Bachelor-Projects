<?php
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
include 'db/connect_to_db.php';
$orderid= $_POST["details"];
$query = "SELECT * FROM order_details JOIN records ON order_details.recordid= records.recordid WHERE orderid=?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}
if (!($stmt->bind_param("i", $orderid))) {
    error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}
if (!($stmt->execute())) {
    error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}
$result = $stmt->get_result();
$orders = [];
if ($result->num_rows == 0) {
    echo "Non hai ancora effettuato alcun ordine.";
} else {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>