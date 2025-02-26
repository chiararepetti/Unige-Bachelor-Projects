<?php
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
if ($_SESSION['role']!='admin' && $_SESSION['role']!='owner'){
    header("Location: errors/403.php");
}
include 'db/connect_to_db.php';

if (isset($_POST['view_details'])) {
    $orderid = $_POST['view_details'];
} else if (isset($_GET['oid'])) {
    $orderid = $_GET['oid'];
} else {
    header("Location: admin_orders.php");
}

$query = "SELECT * FROM order_details JOIN records ON order_details.recordid=records.recordid WHERE orderid=?";

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

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>