<?php
//mysqli_connect("127.0.0.1", "Hello_user", "Hello_password", "Hello_db")
$link = mysqli_connect("localhost", "carshari_user", "#mXc6J!i^7]y", "carshari_database");
if (mysqli_connect_error()) {
    die('ERROR: Unable to connect:' . mysqli_connect_error());
    // echo "<script>window.alert('Hi!')</script>";
}
?>