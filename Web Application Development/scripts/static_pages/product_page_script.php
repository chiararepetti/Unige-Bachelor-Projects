<?php

include 'db/connect_to_db.php';

$recordid = $_GET["recordid"];

$query = "SELECT records.title, records.artist, records.released, records.genre,records.price, records.cover, AVG(rating) AS avgrating FROM records LEFT JOIN review ON records.recordid=review.recordid WHERE records.recordid=? GROUP BY records.recordid";

$stmt = $conn->prepare($query);

if (!$stmt) {
    error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}

if (!($stmt->bind_param("i", $recordid))) {
    error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}

if (!($stmt->execute())) {
    error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($result->num_rows == 0) {
    header("Location: errors/404.php");
} else {
    $title = $row['title'];
    $artist = $row['artist'];
    $released = $row['released'];
    $genre = $row['genre'];
    $price = $row['price'];
    $cover = $row['cover'];
    $avgrating = $row['avgrating'];
}

//giudizio medio
$query = "SELECT recordid, AVG(rating) AS avgrating FROM review WHERE recordid=? GROUP BY recordid";

$stmt = $conn->prepare($query);

if (!$stmt) {
    error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}

if (!($stmt->bind_param("i", $recordid))) {
    error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}

if (!($stmt->execute())) {
    error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}
$result1=$stmt->get_result();

//script di review
$query = "SELECT * FROM review
         NATURAL JOIN users
         WHERE recordid=?";

$stmt = $conn->prepare($query);

if (!$stmt) {
    error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}

if (!($stmt->bind_param("i", $recordid))) {
    error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}

if (!($stmt->execute())) {
    error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
    exit("Something went wrong... try again later.");
}

$result2 = $stmt->get_result();



mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
