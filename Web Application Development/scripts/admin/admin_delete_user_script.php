<?php
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
if ($_SESSION['role']!='admin' && $_SESSION['role']!='owner'){
    header("Location: errors/403.html");
}
if (isset($_POST['delete_user'])) {
    include 'db/connect_to_db.php';

    $userid = $_POST['delete_user'];
    $query="DELETE FROM users WHERE userid=?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "../errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->bind_param("i",$userid)) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: admin_users.php");
}
?>