<?php
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
if ($_SESSION['role']!='admin' && $_SESSION['role']!='owner'){
    header("Location: errors/403.html");
}
if (isset($_POST['submit'])) {
    include 'db/connect_to_db.php';

    $userid = $_POST['submit'];
    $query="SELECT * FROM users WHERE userid=?";
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
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if(!empty($_POST["firstname"])) {
        $firstname = trim($_POST["firstname"]);
    }
    else {
        $firstname = $row['firstname'];
    }
    if(!empty($_POST["lastname"])){
        $lastname = trim($_POST["lastname"]);
    }
    else {
        $lastname = $row['lastname'];
    }
    if(!empty($_POST["email"])){
        $email = trim($_POST["email"]);
    }
    else {
        $email = $row['email'];
    }
    if(!empty($_POST["planet"])) {
        $planet = trim($_POST["planet"]);
    } else {
        $planet = $row['planet'];
    }
    if(!empty($_POST["role"])) {
        $role = trim($_POST["role"]);
    } else {
        $role = $row['role'];
    }

    $query="UPDATE users SET firstname=?, lastname=?, email=?, planet=?, role=? WHERE userid=?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "../errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->bind_param("sssssi", $firstname, $lastname, $email, $planet, $role, $userid)) {
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