<?php
//start session and connect
session_start();
include('my_Connection.php');
$sql="DELETE FROM carsharetrips WHERE trip_id='".$_POST['trip_id']."'";
$result = mysqli_query($link, $sql);
?>