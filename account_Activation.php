<?php

session_start();
include('my_Connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kontoaktivierung</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        h1 {
            color: blueviolet;
        }

        .contactForm {
            border: 1px solid #7c73f6;
            margin-top: 40px;
            border-radius: 15px;
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-offset-1 col-sm-10 contactForm">
                <h1>Kontoaktivierung</h1>
                <?php
                // Show an error msg If email or activation key is missing
                if (!isset($_GET['email']) || !isset($_GET['key'])) {
                    echo '<div class="alert alert-danger">Es ist ein Fehler aufgetreten. Bitte klicken Sie auf den Aktivierungslink, den Sie per E-Mail erhalten haben.</div>';
                    exit;
                } else
                    //Stored in two variables
                    $email = $_GET['email'];
                $key = $_GET['key'];
                //variables for  entering query 
                $email = mysqli_real_escape_string($link, $email);
                $key = mysqli_real_escape_string($link, $key);
                //Run query: set activation field to "activated" for the given email
                $sql = "UPDATE users SET activation='activated' WHERE (email='$email' AND activation='$key') LIMIT 1";
                $result = mysqli_query($link, $sql);
                //If Entry is successful, show success message and call upon user to login
                if (mysqli_affected_rows($link) == 1) {
                    echo '<div class="alert alert-success">Ihr Konto wurde aktiviert.</div>';
                    echo '<a href="index.php" type="button" class="btn-lg btn-sucess">Einloggen<a/>';

                } else {
                    // finally Showing error message
                    echo '<div class="alert alert-danger">Ihr Konto konnte nicht aktiviert werden. Bitte versuchen Sie es später erneut.</div>';
                    echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';

                }
                ?>

            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>