<?php
include 'db/connect_to_db.php';

if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
if ($_SESSION['role']!='admin' && $_SESSION['role']!='owner'){
    header("Location: errors/403.php");
}
if (isset($_POST['ship_order'])) {
    $orderid = $_POST['ship_order'];
    $query = "SELECT * FROM orders WHERE orderid=?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("s", $orderid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    $result = $stmt->get_result();
    if ($result->fetch_assoc()['state'] == 'CONSEGNATO') {
        header("Location: admin_orders.php");
    } else {
        $query = "UPDATE orders SET state='CONSEGNATO', sdate = NOW(), ddate= NOW() WHERE orderid=?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->bind_param("i", $_POST['ship_order']))) {
            error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->execute())) {
            error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        header("Location: admin_orders.php");
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
if (isset($_POST['ship_all'])){
    $state = "IN PREPARAZIONE";
    $query = "SELECT * FROM orders WHERE state=?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("s", $state))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    $result=$stmt->get_result();
    while($row=$result->fetch_assoc()) {
        $orderid = $row['orderid'];
        $query = "UPDATE orders SET state='CONSEGNATO', sdate=NOW(), ddate=NOW() WHERE orderid=?";
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
    }

    header("Location: admin_orders.php");
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
