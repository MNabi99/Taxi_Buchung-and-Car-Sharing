<?php
//<!--Start session-->
session_start();
include('connection.php');

//<!--Check user inputs-->
//    <!--Define error messages-->
$missing_Username = '<p><strong>Please enter a username!</strong></p>';
$missing_Email = '<p><strong>Please enter your email address!</strong></p>';
$invalid_Email = '<p><strong>Please enter a valid email address!</strong></p>';
$missing_Password = '<p><strong>Please enter a Password!</strong></p>';
$invalid_Password = '<p><strong>Your password should be at least 6 characters long and inlcude one capital letter and one number!</strong></p>';
$different_Password = '<p><strong>Passwords don\'t match!</strong></p>';
$missing_Password2 = '<p><strong>Please confirm your password</strong></p>';
$missing_FirstName = '<p><strong>Please enter your firstname!</strong></p>';
$missing_LastName = '<p><strong>Please enter your lastname!</strong></p>';
$missing_Phone = '<p><strong>Please enter your phone number!</strong></p>';
$invalid_PhoneNumber = '<p><strong>Please enter a valid phone number (digits only and less than 15 long)!</strong></p>';
$invalid_Email = '<p><strong>Please enter a valid email address!</strong></p>';
$missing_Gender = '<p><strong>Please select your gender</strong></p>';
$missing_Informaton = '<p><strong>Please share a few more words about yourself.</strong></p>';
//    <!--Get username, email, password, password2-->
//Get username
if (empty($_POST["username"])) {
    $errors .= $missing_Username;
} else {
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
}
//Get firstname
if (empty($_POST["firstname"])) {
    $errors .= $missing_FirstName;
} else {
    $firstname = filter_var($_POST["firstname"], FILTER_SANITIZE_STRING);
}
//Get lastname
if (empty($_POST["lastname"])) {
    $errors .= $missing_LastName;
} else {
    $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);
}
//Get email
if (empty($_POST["email"])) {
    $errors .= $missing_Email;
} else {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors .= $invalid_Email;
    }
}
//Get passwords
if (empty($_POST["password"])) {
    $errors .= $missing_Password;
} elseif (
    !(strlen($_POST["password"]) > 6
        and preg_match('/[A-Z]/', $_POST["password"])
        and preg_match('/[0-9]/', $_POST["password"])
    )
) {
    $errors .= $invalid_Password;
} else {
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    if (empty($_POST["password2"])) {
        $errors .= $missing_Password2;
    } else {
        $password2 = filter_var($_POST["password2"], FILTER_SANITIZE_STRING);
        if ($password !== $password2) {
            $errors .= $different_Password;
        }
    }
}
//Get phone number
if (empty($_POST["phonenumber"])) {
    $errors .= $missing_Phone;
} elseif (preg_match('/\D/', $_POST["phonenumber"])) {
    $errors .= $invalid_PhoneNumber;
} else {
    $phonenumber = filter_var($_POST["phonenumber"], FILTER_SANITIZE_STRING);
}
//Get gender
if (empty($_POST["gender"])) {
    $errors .= $missing_Gender;
} else {
    $gender = $_POST["gender"];
}
// To Get full information
if (empty($_POST["moreinformation"])) {
    $errors .= $missing_Informaton;
} else {
    $moreinformation = filter_var($_POST["moreinformation"], FILTER_SANITIZE_STRING);
}
//If there are any errors show msg
if ($errors) {
    $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $resultMessage;
    exit;
}

// If no errors 

//Variables for the queries
$username = mysqli_real_escape_string($link, $username);
$email = mysqli_real_escape_string($link, $email);
$password = mysqli_real_escape_string($link, $password);
//$password = md5($password);
$password = hash('sha256', $password);
//128 bits -> 32 characters
//256 bits -> 64 characters
//If username exists in the users table print error to show that this user name is arleady available
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo '<div class="alert alert-danger">Error running the query!</div>';
    echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
    exit;
}
$results = mysqli_num_rows($result);
if ($results) {
    echo '<div class="alert alert-danger">Dieser Nutzername ist bereits registriert. Möchten Sie sich anmelden?</div>';
    exit;
}
//If email exists in the users table print error to show that this email is arleady available
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo '<div class="alert alert-danger">Error running the query!</div>';
    exit;
}
$results = mysqli_num_rows($result);
if ($results) {
    echo '<div class="alert alert-danger">Diese E-Mail ist bereits registriert. Möchten Sie sich anmelden?</div>';
    exit;
}
//Create a unique  activation code
$activationKey = bin2hex(openssl_random_pseudo_bytes(16));
//byte: unit of data = 8 bits
//bit: 0 or 1
//16 bytes = 16*8 = 128 bits
//(2*2*2*2)*2*2*2*2*...*2
//16*16*...*16
//32 characters

//Insert user details and activation code in the users table

$sql = "INSERT INTO users (`username`, `email`, `password`, `activation`, `first_name`, `last_name`, `phonenumber`, `gender`, `moreinformation`) VALUES ('$username', '$email', '$password', '$activationKey', '$firstname', '$lastname', '$phonenumber', '$gender', '$moreinformation')";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo '<div class="alert alert-danger">There was an error inserting the users details in the database!</div>';
    exit;
}

//Send an email with a link to activate_New_Email.php with their email and activation code
$message = "Bitte klicken Sie auf diesen Link, um Ihr Konto zu aktivieren:\n\n";
$message .= "http://taxi-Buchung-websitefinal.Hosting/activate.php?email=" . urlencode($email) . "&key=$activationKey";
if (mail($email, 'Confirm your Registration', $message, 'From:' . 'developmentisland@gmail.com')) {
    echo "<div class='alert alert-success'>Vielen Dank für Ihre Anmeldung! Eine Bestätigungs-E-Mail wurde an $email. Pgesendet. Bitte klicken Sie auf den Aktivierungslink, um Ihr Konto zu aktivieren.</div>";
}