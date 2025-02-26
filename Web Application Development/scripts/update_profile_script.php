<?php
$error = "";

if (isset($_POST['submit'])) {
    
    $firstname = !empty($_POST["firstname"]) ? trim($_POST["firstname"]) : $_SESSION["firstname"];
    $lastname = !empty($_POST["lastname"]) ? trim($_POST["lastname"]) : $_SESSION["lastname"];
    $email = !empty($_POST["email"]) ? trim($_POST["email"]) : $_SESSION["email"];
    $planet = isset($_POST["planet"]) && !empty($_POST["planet"]) ? trim($_POST["planet"]) : $_SESSION["planet"];

    $regex_first_last_name = "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u";
    $regex_planet = "/^[A-Za-z0-9-]+$/";

    if (!preg_match($regex_first_last_name, $firstname)) {
        $error = "Nome non valido!";
    } if (!preg_match($regex_first_last_name, $lastname)) {
        $error = "Cognome non valido!";
    } if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Indirizzo email non valido!";
    } if (!empty($planet) && !preg_match($regex_planet, $planet)) {
        $error = "Pianeta non riconosciuto!";
    }// se tutto va bene, aggiorniamo le informazioni!
    else {
        include 'db/connect_to_db.php';
        if (!empty($planet)) {
            $query = "UPDATE users SET firstname=?, lastname=?, email=?, planet=? WHERE userid=?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "../errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            if (!$stmt->bind_param("ssssi", $firstname, $lastname, $email, $planet, $_SESSION["id"])) {
                error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
        } else {
            $query = "UPDATE users SET firstname=?, lastname=?, email=? WHERE userid=?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "../errors/errors.log");
                exit("Something went wrong... try again later.");
            }
            if (!$stmt->bind_param("sssi", $firstname, $lastname, $email, $_SESSION["id"])) {
                error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
        }
        if (!$stmt->execute()) {
            if ($stmt->errno == 1062) {
                // 1062 è il codice che si verifica quando esiste già una mail registrata
                $error = "Esiste già un account registrato che utilizza questo indirizzo email!";
            } else {
                error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                exit("Something went wrong... try again later.");
            }
        } else if ($stmt->affected_rows == 0) {
            $error = "Qualcosa è andato storto, riprova!";
        } else {
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['email'] = $email;
            if (!empty($planet)) {
                $_SESSION['planet'] = $planet;
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: show_profile.php");
        }
    }
}
?>
