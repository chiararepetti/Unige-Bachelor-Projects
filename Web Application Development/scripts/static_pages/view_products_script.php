<?php
include 'db/connect_to_db.php';

$search = "";
if (isset($_GET['filter'])){
    $filter = $_GET['filter'];
} else {
    $filter = "released DESC";
}

if (isset($_GET['search'])){
    $search = trim($_GET["search"]);
    $words = explode(' ', $search);

    $query = "SELECT records.*, AVG(review.rating) AS avgrating FROM records 
              LEFT JOIN review ON records.recordid = review.recordid WHERE ";
    $conditions = [];
    foreach ($words as $word) {
        $conditions[] = "(artist LIKE '%$word%' OR title LIKE '%$word%' OR genre LIKE '%$word%')";
    }
    $query .= implode(' AND ', $conditions);
    $query .= " GROUP BY records.recordid ORDER BY $filter";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    $result = $stmt->get_result();

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    $query = "SELECT records.*, AVG(review.rating) AS avgrating FROM records 
              LEFT JOIN review ON records.recordid = review.recordid 
              GROUP BY records.recordid ORDER BY $filter";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!($stmt->execute())) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    $result = $stmt->get_result();

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
