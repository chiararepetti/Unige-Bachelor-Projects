<?php
$error="";
if (isset($_POST['submit'])) {
    if (empty($_POST["title"])
        || empty($_POST["artist"])
        || empty($_POST["released"])
        || empty($_POST["genre"])
        || empty($_POST["price"])
        || empty($_POST["path"])) {
        $error = "Attenzione! Inserisci tutti i campi obbligatori.";
        exit("Something went wrong... try again later.");
    } else {
        $title = trim($_POST['title']);
        $artist = trim($_POST['artist']);
        $released = $_POST['released'];
        $genre = trim($_POST['genre']);
        $price = $_POST['price'];
        $path = trim($_POST['path']);
    }

    if(isset($_FILES['file'])) {
        $file = $_FILES['file'];

        if($file['error'] === UPLOAD_ERR_OK) {
            $fileName = $file['name'];
            $tempFilePath = $file['tmp_name'];

            $maxFileSize = 5 * 1024 * 1024;
            if($file['size'] > $maxFileSize) {
                $error = "Il file è troppo grande. La dimensione massima consentita è 5MB.";
                exit("Something went wrong... try again later.");
            }

            $uploadDirectory = '../../images/';
            if (!file_exists($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            $destinationFilePath = $uploadDirectory . $fileName;

            if(!move_uploaded_file($tempFilePath, $destinationFilePath)) {
                $error = "Si è verificato un errore durante il caricamento del file.";
                exit("Something went wrong... try again later.");
            }
            } else {
                $error = "Si è verificato un errore durante il caricamento del file.";
                exit("Something went wrong... try again later.");
        }
    }

    include '../connect_to_db.php';

    $query="INSERT INTO records (title, artist, released, genre, price, cover) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "../errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->bind_param("ssssds",$title, $artist, $released, $genre, $price, $path)) {
        error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
        exit("Something went wrong... try again later.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: ../../admin_products.php");
}
?>