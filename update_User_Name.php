<?php

//session and connection
session_start();
include ('my_Connection.php');

//get user_id
$id = $_SESSION['user_id'];

//Get username 
$username = $_POST['username'];

//Run query and update username
$sql = "UPDATE users SET username='$username' WHERE user_id='$id'";
$result = mysqli_query($link, $sql);

if(!$result){
    echo '<div class="alert alert-danger"> error !</div>';
}
