<?php
//Start session
session_start();
//Connect 
include("my_Connection.php");
//Check user inputs 
$missing_User_Email = '<p><stong>Bitte geben Sie Ihre E-Mail Adresse ein!</strong></p>';
$missing_User_Password = '<p><stong>Geben Sie bitte Ihr Passwort ein!</strong></p>';

// Errors variable
if (empty($_POST["loginemail"])) {
    $errors .= $missing_User_Email;
} else {
    $email = filter_var($_POST["loginemail"], FILTER_SANITIZE_EMAIL);
}
if (empty($_POST["loginpassword"])) {
    $errors .= $missing_User_Password;
} else {
    $password = filter_var($_POST["loginpassword"], FILTER_SANITIZE_STRING);
}
//print error message If there are any errors
if ($errors) {
    $result_Errors_Message = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $result_Errors_Message;
} else {
    //else: No errors
    //Variables to query
    $email = mysqli_real_escape_string($link, $email);
    $password = mysqli_real_escape_string($link, $password);
    $password = hash('sha256', $password);
    //To check email & password  if exists
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND activation='activated'";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        echo '<div class="alert alert-danger">Fehler bei der Abfrage!</div>';
        exit;
    }
    //If email & password does not match Show error msg
    $count = mysqli_num_rows($result);
    if ($count !== 1) {
        echo '<div class="alert alert-danger">Falscher Benutzername oder falsches Passwort</div>';
    } else {
        //User Login and Set session variables
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];

        if (empty($_POST['rememberme'])) {
            //If remember me is not checked
            echo "success";
        } else {
            //Two variables $authentificator_Code_1 and $authentificator_Code_2
            $authentificator_Code_1 = bin2hex(openssl_random_pseudo_bytes(10));
            //2*2*...*2
            $authentificator_Code_2 = openssl_random_pseudo_bytes(20);
            //Store cookie
            function method_Funtion_1($a, $b)
            {
                $c = $a . "," . bin2hex($b);
                return $c;
            }
            $cookieValue = method_Funtion_1($authentificator_Code_1, $authentificator_Code_2);
            setcookie(
                "rememberme",
                $cookieValue,
                time() + 1296000
            );

            //Run query to store them in rememberme table
            function method_Funtion_2($a)
            {
                $b = hash('sha256', $a);
                return $b;
            }
            $method_Funtion_2_authentificator2 = method_Funtion_2($authentificator_Code_2);
            $user_id = $_SESSION['user_id'];
            $expiration = date('Y-m-d H:i:s', time() + 1296000);

            $sql = "INSERT INTO rememberme
        (`authentificator_Code_1`, `method_Funtion_2_authentificator2`, `user_id`, `expires`)
        VALUES
        ('$authentificator_Code_1', '$method_Funtion_2_authentificator2', '$user_id', '$expiration')";
            $result = mysqli_query($link, $sql);
            if (!$result) {
                echo '<div class="alert alert-danger">Beim Speichern der Daten ist ein Fehler aufgetreten,.</div>';
            } else {
                echo "success";
            }
        }
    }
}
