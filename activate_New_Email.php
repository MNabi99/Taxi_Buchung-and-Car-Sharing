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
    <title>Neue E-Mail-Aktivierung</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-offset-1 col-sm-10 contactForm">
                <h1>Email-Aktivierung</h1>
                <?php
                //in case If activation key, given_Email, new email missing show an error
                if (!isset($_GET['given_Email']) || !isset($_GET['new_Email']) || !isset($_GET['key'])) {
                    echo '<div class="alert alert-danger">THier ist ein Fehler aufgetreten. Bitte klicken Sie auf den Link, den Sie per E-Mail erhalten haben.</div>';
                    exit;
                } else
                    //Stors variables
                    $given_Email = $_GET['given_Email'];
                $new_Email = $_GET['new_Email'];
                $key = $_GET['key'];
                //Variables for the query
                $given_Email = mysqli_real_escape_string($link, $given_Email);
                $new_Email = mysqli_real_escape_string($link, $new_Email);
                $key = mysqli_real_escape_string($link, $key);
                //Run query: To update given_Email
                $sql = "UPDATE users SET given_Email='$new_Email', activation2='0' WHERE (given_Email='$given_Email' AND activation2='$key') LIMIT 1";
                $result = mysqli_query($link, $sql);
                //Show success message, If query is successful,
                if (mysqli_affected_rows($link) == 1) {
                    session_destroy();
                    setcookie("rememeberme", "", time() - 3600);
                    echo '<div class="alert alert-success">Ihre E-Mail wurde aktualisiert.</div>';
                    echo '<a href="index.php" type="button" class="btn-lg btn-sucess">Log in<a/>';

                } else {
                    //Show error message
                    echo '<div class="alert alert-danger">Ihre E-Mail konnte nicht aktualisiert werden. Bitte versuchen Sie es später noch einmal.</div>';
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