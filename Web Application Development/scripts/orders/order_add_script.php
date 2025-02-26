<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
}
if (!isset($_POST["add_order"])) {
    header("Location: checkout.php");
} else {
    include '../connect_to_db.php';

    $cartid = $_SESSION['cart'];
    $userid = $_SESSION['id'];
    $total = $_POST['add_order'];
    $state = 'IN PREPARAZIONE';

    $query = "INSERT INTO orders(userid, total, state) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("ids", $userid, $total, $state))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    $orderid = $conn->insert_id;

    $query = "SELECT * FROM cart_details JOIN records ON cart_details.recordid = records.recordid WHERE cartid=?";
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

    while ($row = $result->fetch_assoc()) {
        $recordid = $row['recordid'];
        $quantity = $row['quantity'];
        $total_per_record = $row['price'];

        $query = "INSERT INTO order_details(orderid, recordid, quantity, total_per_record) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->bind_param("iiid", $orderid, $recordid, $quantity, $total_per_record))) {
            error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->execute())) {
            error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
    }

    $query = "DELETE FROM cart_details WHERE cartid=?";
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

    //svuotiamo il carrello
    $query = "UPDATE carts SET total=? WHERE cartid=?";
    $new_total = 0;
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("di",$new_total, $cartid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: ../../order_page.php?order=added");
}
?>
}