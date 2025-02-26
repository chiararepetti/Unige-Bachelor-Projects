<?php
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
if ($_SESSION['role']!='admin' && $_SESSION['role']!='owner'){
    header("Location: errors/403.html");
}

include 'db/connect_to_db.php';

if(isset($_POST['delete_one'])) {
    $orderdetailid = $_POST['delete_one'];
    $query = "SELECT * FROM order_details JOIN orders ON orders.orderid=order_details.orderid WHERE orderdetailid=?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("i", $orderdetailid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $orderid = $row['orderid'];

    $new_quantity = floatval($row['quantity']-1);
    $new_total = floatval($row['total']-$row['total_per_record']);

    //se quantity va a 0, cancello il dettaglio
    if($new_quantity == 0) {
        $query = "DELETE FROM order_details WHERE orderdetailid=?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->bind_param("i", $orderdetailid))) {
            error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->execute())) {
            error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
    } else {
        $query = "UPDATE order_details SET quantity=? WHERE orderdetailid=?";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }

        if (!($stmt->bind_param("di", $new_quantity,$orderdetailid))) {
            error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }

        if (!($stmt->execute())) {
            error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
    }
    $query = "UPDATE orders SET total=? WHERE orderid=?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!($stmt->bind_param("di", $new_total, $orderid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    // se nn ci sono altri dettagli elimino l'ordine
    $query = "SELECT * FROM order_details WHERE orderid=?";

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
    if ($result->num_rows == 0) {
        if (!$result->fetch_assoc()) {
            $query = "DELETE FROM orders WHERE orderid=?";

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
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: admin_orders.php");
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: admin_order_details.php?oid=$orderid");

} else if (isset($_POST['delete_detail'])) {
    $orderdetailid = $_POST['delete_detail'];

    $query = "SELECT * FROM order_details JOIN orders ON orders.orderid=order_details.orderid WHERE orderdetailid=?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("i", $orderdetailid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $orderid = $row['orderid'];

    $total_to_remove = floatval($row['total_per_record']*$row['quantity']);
    $new_total = floatval($row['total']-$total_to_remove);

    $query = "DELETE FROM order_details WHERE orderdetailid=?";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!($stmt->bind_param("i", $orderdetailid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    $query = "UPDATE orders SET total=? WHERE orderid=?";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!($stmt->bind_param("di", $new_total, $orderid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    // se nn ci sono altri dettagli elimino l'ordine
    $query = "SELECT * FROM order_details WHERE orderid=?";

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
    if ($result->num_rows == 0) {
        if (!$result->fetch_assoc()) {
            $query = "DELETE FROM orders WHERE orderid=?";

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
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: admin_orders.php");
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: admin_order_details.php?oid=$orderid");
}
?>
