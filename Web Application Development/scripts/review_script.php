<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$error="";

if(isset($_POST['review'])) {
    if(!isset($_SESSION['login'])){
        header("Location: ../login.php");
    }
    $userid = $_SESSION['id'];
    $recordid = $_POST['review'];
    $rating = $_POST["rating"];
    $opinion = $_POST["opinion"];

    include 'connect_to_db.php';

    $query = "SELECT * FROM orders NATURAL JOIN order_details WHERE orders.userid=? AND order_details.recordid=?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("ii", $_SESSION['id'], $recordid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $error = "Non puoi recensire un articolo che non hai acquistato!";
        header("Location: ../product_page.php?recordid=$recordid");
    } else if ($result->fetch_assoc()['state'] != 'CONSEGNATO') {
        $error = "Non puoi recensire un articolo che non hai ancora ricevuto!";
        header("Location: ../product_page.php?recordid=$recordid");
    } else {
        $query = "SELECT * FROM review WHERE recordid=? AND userid=?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->bind_param("ii", $recordid, $userid))) {
            error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        if (!($stmt->execute())) {
            error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
            exit("Something went wrong... try again later.");
        }
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $error = "Non puoi recensire un articolo due volte!";
            header("Location: ../product_page.php?recordid=$recordid");
        } else {
            if ($rating <= 0) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location: ../product_page.php?recordid=$recordid");
            }
            $query = "INSERT INTO review(recordid, userid, rating, opinion) VALUES (?, ?, ?, ?) ";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            if (!($stmt->bind_param("iiis", $recordid, $userid, $rating, $opinion))) {
                error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            if (!($stmt->execute())) {
                error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: ../product_page.php?recordid=$recordid");
} else if(isset($_POST['delete_review'])){
    if(!isset($_SESSION['login'])){
        header("Location: ../login.php");
    }
    $reviewid = $_POST["delete_review"];

    include 'connect_to_db.php';

    $query = "SELECT * FROM review WHERE reviewid=?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("i", $reviewid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $recordid = $row["recordid"];

    $query = "DELETE FROM review WHERE reviewid=?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->bind_param("i", $reviewid))) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    header("Location: ../product_page.php?recordid=$recordid");
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>