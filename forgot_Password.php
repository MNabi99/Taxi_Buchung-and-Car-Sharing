<?php
//Start session
session_start();
//Database concection
include('my_Connection.php');

//Check user inputs
//error messages
$missing_Email = '<p><strong>Bitte geben Sie Ihre E-Mail Adresse ein!</strong></p>';
$invalid_Email = '<p><strong>Bitte geben Sie eine gültige E-Mail Adresse ein!</strong></p>';
//Get email
//To atore errors in errors variable
if (empty($_POST["forgot_Email"])) {
    $errors .= $missing_Email;
} else {
    $email = filter_var($_POST["forgot_Email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors .= $invalid_Email;
    }
}

//If any errors
//print error message
if ($errors) {
    $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $resultMessage;
    exit;
}
//else:  No errors
//variables for the query
$email = mysqli_real_escape_string($link, $email);
//Run query to check if email exists in table
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo '<div class="alert alert-danger">Error</div>';
    exit;
}
$count = mysqli_num_rows($result);
//If the email not found
//print error mesg
if ($count != 1) {
    echo '<div class="alert alert-danger">Diese E-Mail existiert nicht!</div>';
    exit;
}

else
//get the user_Id
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$user_id = $row['user_id'];
//Generate a unique  activation code
$key = bin2hex(openssl_random_pseudo_bytes(16));
//Insert user details and activation code in the forgotpassword table
$time = time();
$status = 'pending';
$sql = "INSERT INTO forgotpassword (`user_id`, `rkey`, `time`, `status`) VALUES ('$user_id', '$key', '$time', '$status')";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo '<div class="alert alert-danger">Es ist ein Fehler beim Einfügen der Benutzerdaten aufgetreten!</div>';
    exit;
}

//Send email with link to reset_User_Password.php with user id and activation code

$message = "Bitte klicken Sie auf diesen Link, um Ihr Passwort zurückzusetzen:\n\n";
$message .= "http://Taxi-Buchung.hosting.com/reset_User_Password.php?user_id=$user_id&key=$key";
if (mail($email, 'Setzen Sie Ihr Passwort zurück', $message, 'From:' . 'soft_OutletMetzin.de')) {
    // print success messageIf email sent successfully
 
    echo "<div class='alert alert-success'>Eine E-Mail wurde gesendet an $email. Bitte klicken Sie auf den Link, um Ihr Passwort zurückzusetzen.</div>";
}

