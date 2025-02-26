<?php
	$error = "";

	if (isset($_POST['submit'])) {
        if (empty($_POST["email"]) || empty($_POST["pass"])) {
            $error = "Attenzione! Inserisci tutti i dati.";
        } else {
            $email = trim($_POST["email"]);
            $pass = trim($_POST["pass"]);

            $regex_password = "/^[0-9A-Za-z!@&%$*#]{1,12}$/";

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Email non valida!";
            } else if (!preg_match($regex_password, $pass)) {
                $error = "Devi usare una password valida.";
            } else {
                include 'db/connect_to_db.php';

                $query = "SELECT * FROM users JOIN carts ON users.userid=carts.userid WHERE email=?";

                $stmt = $conn->prepare($query);

                if (!$stmt) {
                    error_log("Prepare failed: " . $conn->error . " (" . $conn->errno . ")", 0, "errors/errors.log");
                    exit("Something went wrong... try again later.");
                }

                if (!($stmt->bind_param("s", $email))) {
                    error_log("Binding parameters failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                    exit("Something went wrong... try again later.");
                }

                if (!($stmt->execute())) {
                    error_log("Execute failed: " . $stmt->error . " (" . $stmt->errno . ")", 0, "errors/errors.log");
                    exit("Something went wrong... try again later.");
                }

                $result = $stmt->get_result();
                $rows_counter = $result->num_rows;
                $row = $result->fetch_assoc();

                if ($rows_counter == 1) {
                    if (password_verify($pass, $row['pass'])) {
                        $_SESSION['login'] = true;
                        $_SESSION['id'] = $row['userid'];
                        $_SESSION['email'] = $email;
                        $_SESSION['firstname'] = $row['firstname'];
                        $_SESSION['lastname'] = $row['lastname'];
                        $_SESSION['planet'] = $row['planet'];
                        $_SESSION['role'] = $row['role'];
                        $_SESSION['cart'] = $row['cartid'];

                        mysqli_stmt_close($stmt);
                        mysqli_close($conn);
                        if (isset($_SESSION['last_visited'])){
                            header("Location: ". $_SESSION['last_visited'] ." ");
                        } else {
                            header("Location: index.php");
                        }

                    } else {
                        $error = "Email e/o password errate!";
                    }
                } else {
                    $error = "Email e/o password errate!";
                }
            }
        }
    }
?>