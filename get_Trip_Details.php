<?php
//start session and connect
session_start();
include('my_Connection.php');

$sql="SELECT * FROM Taxi_Buchung WHERE trip_id='".$_POST['trip_id']."'"; 
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$array = array("trip_id"=>$row['trip_id'], "trip_Departure"=>$row['trip_Departure'], "trip_Destination"=>$row['trip_Destination'], "price"=>$row['price'], "seats_Available"=>$row['seats_Available'], "regular"=>$row['regular'], "date"=>$row['date'], "time"=>$row['time'], "monday"=>$row['monday'], "tuesday"=>$row['tuesday'], "wednesday"=>$row['wednesday'], "thursday"=>$row['thursday'], "friday"=>$row['friday'], "saturday"=>$row['saturday'], "sunday"=>$row['sunday']);
echo json_encode($array);

?>