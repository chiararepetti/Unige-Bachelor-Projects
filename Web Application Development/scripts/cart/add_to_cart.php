<?php
if (isset($_POST['add_to_cart'])) {
    $recordid = $_POST['add_to_cart'];

    if (!empty($_POST['quantity'])) {
        $quantity = $_POST['quantity'];
    } else {
        $quantity = 1;
    }

    if (!isset($_SESSION['login'])){
        header("Location: login.php");
    }

    $cartid = $_SESSION['cart'];

    include 'db/connect_to_db.php';

    $query = "SELECT * FROM cart_details WHERE cartid=?;";

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
        if ($row['recordid'] == $recordid) {
            $new_quantity = $quantity + $row['quantity'];

            $query = "UPDATE cart_details SET quantity=? WHERE cartid=? AND recordid=?;";

            $stmt = $conn->prepare($query);

            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            if (!$stmt->bind_param("iii", $new_quantity, $cartid, $recordid)) {
                error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: product_page.php?recordid=".$recordid);
        }
    }
    $query = "INSERT INTO cart_details(cartid, recordid, quantity) VALUES(?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!$stmt->bind_param("iii", $cartid, $recordid, $quantity)) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: product_page.php?recordid=".$recordid);
}
?>