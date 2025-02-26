<?php
$error = "";
$success = "";

if (isset($_POST['submit'])) {
    if(empty($_POST["pass"]) || empty($_POST["new-pass"]) || empty($_POST["confirm"])) {
        $error = "Non hai inserito tutti i dati. Se non vuoi cambiare password, puoi tornare indietro!";
    } else {
        $old_pass = trim($_POST["pass"]);
        $new_pass = trim($_POST["new-pass"]);
        $confirm = trim($_POST["confirm"]);

        $regex_password = "/^[0-9A-Za-z!@&%$*#]{1,12}$/";

        // input validation
        if (!preg_match($regex_password, $old_pass)) {
            $error = "Hai inserito come vecchia password una password errata.";
        } else if (!preg_match($regex_password, $new_pass)) {
            $error = "Hai inserito una nuova password non valida.";
        } else if ($new_pass != $confirm) {
            $error = "Nuova password e conferma password non sono uguali.";
        } else if ($new_pass == $old_pass) {
        $error = "Non puoi utilizzare come nuova password la vecchia password!";
        }
        else {
            include 'db/connect_to_db.php';
            $query = "SELECT pass FROM users WHERE userid=?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "../errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            if (!$stmt->bind_param("i", $_SESSION["id"])) {
                error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            // controlliamo che la vecchia password inserita sia corretta
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if (!password_verify($old_pass, $row["pass"])) {
                $error = "Hai inserito come vecchia password una password errata.";
            } else {
                // aggiorniamo la password
                $new_db_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                $query="UPDATE users SET pass=? WHERE userid=?";
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "../errors/errors.log");
                    exit("Something went wrong... try again later.");
                }
                if (!$stmt->bind_param("si", $new_db_pass, $_SESSION["id"])) {
                    error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                    exit("Something went wrong... try again later.");
                }
                if (!$stmt->execute()) {
                    error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                    exit("Something went wrong... try again later.");
                }
                if($stmt->affected_rows==0){
                    $error = "Qualcosa è andato storto, riprova.";
                } else {
                    $success = 'Modifiche apportate con successo! Puoi tornare al tuo profilo';
                }
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
            }
        }
    }
}
?>