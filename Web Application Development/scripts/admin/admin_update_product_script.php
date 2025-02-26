<?php
if (!isset($_SESSION['login'])){
    header("Location: login.php");
}
if ($_SESSION['role']!='admin' && $_SESSION['role']!='owner'){
    header("Location: errors/403.html");
}
if (isset($_POST['submit'])) {
    include 'db/connect_to_db.php';

    $recordid = $_POST['submit'];
    $query="SELECT * FROM records WHERE recordid=?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "../errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->bind_param("i",$recordid)) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if(!empty($_POST["title"])) {
        $title = trim($_POST["title"]);
    }
    else {
        $title = $row['title'];
    }
    if(!empty($_POST["artist"])){
        $artist = trim($_POST["artist"]);
    }
    else {
        $artist = $row['artist'];
    }
    if(!empty($_POST["released"])){
        $released = $_POST["released"];
    }
    else {
        $released = $row['released'];
    }
    if(!empty($_POST["genre"])) {
        $genre = trim($_POST["genre"]);
    } else {
        $genre = $row['genre'];
    }
    if(!empty($_POST["price"])) {
        $price = $_POST["price"];
    } else {
        $price = $row['price'];
    }

    $query="UPDATE records SET title=?, artist=?, released=?, genre=?, price=? WHERE recordid=?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "../errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->bind_param("ssssdi", $title, $artist, $released, $genre, $price, $recordid)) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: admin_products.php");
}
?>