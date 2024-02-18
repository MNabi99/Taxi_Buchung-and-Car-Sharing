<!--Receives: user_id, generated key to reset password, password1 and password2-->
<!--Resets password for user_id if all checks are correct-->
<?php
session_start();
include('my_Connection.php');
//user_id or key is missing
if (!isset($_POST['user_id']) || !isset($_POST['key'])) {
    echo '<div class="alert alert-danger">Es ist ein Fehler aufgetreten. Bitte klicken Sie auf den Link, den Sie per E-Mail erhalten haben.</div>';
    exit;
} else {
    // variables
    $user_id = $_POST['user_id'];
    $key = $_POST['key'];
    $time = time() - 86400;
    //variables for the query
    $user_id = mysqli_real_escape_string($link, $user_id);
    $key = mysqli_real_escape_string($link, $key);
    //Run Query: Check combination of user_id & key exists and less than 24h old
    $sql = "SELECT user_id FROM forgotpassword WHERE rkey='$key' AND user_id='$user_id' AND time > '$time' AND status='pending'";
    $result = mysqli_query($link, $sql);
}
if (!$result) {
    echo '<div class="alert alert-danger">Error msg</div>';
    exit;
}
//If combination does not exist show an error message
$count = mysqli_num_rows($result);
if ($count !== 1) {
    echo '<div class="alert alert-danger">Bitte versuchen Sie es erneut.</div>';
    exit;
}

//Define error messages
$missingPassword = '<p><strong> Geben Sie ein Passwort ein!</strong></p>';
$invalidPassword = '<p><strong>Ihr Passwort sollte mindestens 6 Zeichen lang sein und einen Großbuchstaben und eine Zahl enthalten!</strong></p>';
$differentPassword = '<p><strong>Das Passwort stimmt nicht überein!</strong></p>';
$missingPassword2 = '<p><strong>Bitte bestätigen Sie Ihr Passwort</strong></p>';

//Get passwords
if (empty($_POST["password"])) {
    $errors .= $missingPassword;
} elseif (
    !(strlen($_POST["password"]) > 6
        and preg_match('/[A-Z]/', $_POST["password"])
        and preg_match('/[0-9]/', $_POST["password"])
    )
) {
    $errors .= $invalidPassword;
} else {
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    if (empty($_POST["password2"])) {
        $errors .= $missingPassword2;
    } else {
        $password2 = filter_var($_POST["password2"], FILTER_SANITIZE_STRING);
        if ($password !== $password2) {
            $errors .= $differentPassword;
        }
    }
}

//If any errors show error msg
if ($errors) {
    $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
    echo $resultMessage;
    exit;
}

//Variables for the query
$password = mysqli_real_escape_string($link, $password);
$password = hash('sha256', $password);
$user_id = mysqli_real_escape_string($link, $user_id);

//Query: Update users password in the users table
$sql = "UPDATE users SET password='$password' WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo '<div class="alert alert-danger">Es gab ein Problem beim Speichern des neuen Passworts </div>';
    echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
    exit;
}

//set the key status to "used" in the forgotpassword table to prevent the key from being used twice
$sql = "UPDATE forgotpassword SET status='used' WHERE rkey='$key' AND user_id='$user_id'";
$result = mysqli_query($link, $sql);
if (!$result) {
    echo '<div class="alert alert-danger">Error Msg</div>';
} else {
    echo '<div class="alert alert-success">Ihr Passwort wurde erfolgreich aktualisiert!<a href="index.php">Login</a></div>';
}
?>