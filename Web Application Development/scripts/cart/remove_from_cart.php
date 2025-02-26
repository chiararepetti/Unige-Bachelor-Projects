<?php
include 'db/connect_to_db.php';

$cartid = $_SESSION['cart'];

if (isset($_POST['flush_cart'])) {
    $query = "DELETE FROM cart_details WHERE cartid=?;";
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

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: cart.php");
} else if (isset($_POST['remove_one'])) {
    $recordid = $_POST['remove_one'];

    $query = "SELECT quantity FROM cart_details WHERE cartid=? AND recordid=?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("ii", $cartid, $recordid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    $result=$stmt->get_result();
    $row=$result->fetch_assoc();

    if ($row['quantity']==1){
        $query = "DELETE FROM cart_details WHERE cartid=? AND recordid=?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->bind_param("ii", $cartid, $recordid))) {
            error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->execute())) {
            error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: cart.php");
    } else {
        $query = "UPDATE cart_details SET quantity=quantity-1 WHERE cartid=? AND recordid=?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->bind_param("ii", $cartid, $recordid))) {
            error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->execute())) {
            error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: cart.php");
    }

} else if (isset($_POST['remove_all'])) {
    $recordid = $_POST['remove_all'];

    $query = "DELETE FROM cart_details WHERE cartid=? AND recordid=?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("ii", $cartid, $recordid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: cart.php");
}
?>