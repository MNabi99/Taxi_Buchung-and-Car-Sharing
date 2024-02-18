<?php
//start session and connect
session_start();
include('my_Connection.php');

//define error messages
$missing_Current_Password = '<p><strong>abc!</strong></p>';
$incorrect_Current_Password = '<p><strong>abc!</strong></p>';
$missing_Password = '<p><strong>abc!</strong></p>';
$invalid_Password = '<p><strong>xyz!</strong></p>';
$different_Password = '<p><strong>hgh</strong></p>';
$missing_Password2 = '<p><strong>yxz</strong></p>';

//check for errors
if (empty($_POST["currentpassword"])) {
    $errors .= $missing_Current_Password;
} else {
    $currentPassword = $_POST["currentpassword"];
    $currentPassword = filter_var($currentPassword, FILTER_SANITIZE_STRING);
    $currentPassword = mysqli_real_escape_string($link, $currentPassword);
    $currentPassword = hash('sha256', $currentPassword);
    //check if given password is correct
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT password FROM users WHERE user_id='$user_id'";
    $result = mysqli_query($link, $sql);
    $count = mysqli_num_rows($result);
    if ($count !== 1) {
        echo '<div class="alert alert-danger">Error Msg</div>';
    } else {
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);
        if ($currentPassword != $row['password']) {
            $errors .= $incorrect_Current_Password;
        }
    }

}

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

// To Do code for full functioning