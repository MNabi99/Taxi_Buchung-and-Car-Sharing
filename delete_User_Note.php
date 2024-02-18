<?php
session_start();
include('my_Connection.php');

//To get id of a given note through Ajax process
$note_id = $_POST['id'];
// To delete the note run a query 
$sql = "DELETE FROM notes WHERE id = $note_id";
$result = mysqli_query($link, $sql);
if(!$result){
    echo 'error';   
}

