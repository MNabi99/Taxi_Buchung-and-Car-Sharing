<?php
//start session and connect
session_start();
include('my_Connection.php');

//Viriable to define all given error messages
$missing_Departure_Place = '<p><strong>Bitte geben Sie Ihre Abreise ein!</strong></p>';
$invaliddeparture = '<p><strong>Bitte geben Sie eine gültige Abfahrt ein!</strong></p>';
$missing_Destination_Place = '<p><strong>Bitte geben Sie Ihr Reiseziel ein!</strong></p>';
$invalid_Destination_Place = '<p><strong>Bitte geben Sie ein gültiges Reiseziel ein!</strong></p>';
$missing_Price_Details = '<p><strong>Bitte wählen Sie einen Preis pro Sitzplatz!</strong></p>';
$invalid_Price_Details = '<p><strong>Bitte wählen Sie einen gültigen Preis pro Sitzplatz nur mit Zahlen!</strong></p>';
$missing_Seats_Available = '<p><strong>Bitte wählen Sie die Anzahl der verfügbaren Sitzplätze!</strong></p>';
$invalid_Seat_Abailable_Details = '<p><strong>Die Anzahl der verfügbaren Sitzplätze sollte nur Ziffern enthalten!</strong></p>';
$missing_Frequency = '<p><strong>Bitte wählen Sie eine Frequenz aus!</strong></p>';
$missing_Days = '<p><strong>Bitte wählen Sie mindestens einen Wochentag aus!</strong></p>';
$missing_Date = '<p><strong>Bitte wählen Sie ein Datum für Ihre Reise!</strong></p>';
$missing_Time = '<p><strong>Bitte wählen Sie eine Zeit für Ihre Reise!</strong></p>';

//Get inputs:
$trip_Departure = $_POST["trip_Departure"];
$trip_Destination = $_POST["trip_Destination"];
$price = $_POST["price"];
$seats_Available = $_POST["seats_Available"];
$regular = $_POST["regular"];
$date = $_POST["date"];
$time = $_POST["time"];
$monday = $_POST["monday"];
$tuesday = $_POST["tuesday"];
$wednesday = $_POST["wednesday"];
$thursday = $_POST["thursday"];
$friday = $_POST["friday"];
$saturday = $_POST["saturday"];
$sunday = $_POST["sunday"];

// To check departure Latitude and departure Longitude coordinates
if (!isset($_POST["departure_Latitude"]) or !isset($_POST["departure_Longitude"])) {
    $errors .= $invalid_Departure_Place;
} else {
    $departure_Latitude = $_POST["departure_Latitude"];
    $departure_Longitude = $_POST["departure_Longitude"];
}

if (!isset($_POST["destination_Latitude"]) or !isset($_POST["destination_Longitude"])) {
    $errors .= $invalid_Destination_Place;
} else {
    $destination_Latitude = $_POST["destination_Latitude"];
    $destination_Longitude = $_POST["destination_Longitude"];
}


//Check Trip departure:
if (!$trip_Departure) {
    $errors .= $missing_Departure_Place;
} else {
    $trip_Departure = filter_var($trip_Departure, FILTER_SANITIZE_STRING);
}

//Check trip destination:
if (!$trip_Destination) {
    $errors .= $missing_Destination_Place;
} else {
    $trip_Destination = filter_var($trip_Destination, FILTER_SANITIZE_STRING);
}

//Check price details
if (!$price) {
    $errors .= $missing_Price_Details;
} elseif (
    preg_match('/\D/', $price)  // can slso be use ctype_digit($price)
) {
    $errors .= $invalid_Price_Details;
} else {
    $price = filter_var($price, FILTER_SANITIZE_STRING);
}

//Check Seats if  available
if (!$seats_Available) {
    $errors .= $missing_Seats_Available;
} elseif (
    preg_match('/\D/', $seats_Available)  // can be use ctype_digit($seats_Available)
) {
    $errors .= $invalid_Seat_Abailable_Details;
} else {
    $seats_Available = filter_var($seats_Available, FILTER_SANITIZE_STRING);
}

//Check with all regular data
if (!$regular) {
    $errors .= $missing_Frequency;
} elseif ($regular == "Y") {
    if (!$monday && !$tuesday && !$wednesday && !$thursday && !$friday && !$saturday && !$sunday) {
        $errors .= $missing_Days;
    }
    if (!$time) {
        $errors .= $missing_Time;
    }
} elseif ($regular == "N") {
    if (!$date) {
        $errors .= $missing_Date;
    }
    if (!$time) {
        $errors .= $missing_Time;
    }
}

//print error message, if there is any error 
if ($errors) {
    $resultMessage = "<div class='alert alert-danger'>$errors</div>";
    echo $resultMessage;
} else {
    // if no errors occours,  some variables for query
    $tbl_name = 'carsharetrips';
    $trip_Departure = mysqli_real_escape_string($link, $trip_Departure);
    $trip_Destination = mysqli_real_escape_string($link, $trip_Destination);
    if ($regular == "Y") {
        //regular trip query
        $sql = "INSERT INTO $tbl_name (`user_id`,`trip_Departure`, `departure_Longitude`, `departure_Latitude`, `trip_Destination`, `destination_Longitude`, `destination_Latitude`, `price`, `seats_Available`, `regular`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `time`) VALUES ('" . $_SESSION['user_id'] . "', '$departure','$departure_Longitude','$departure_Latitude','$trip_Destination','$destination_Longitude','$destination_Latitude','$price','$seats_Available','$regular','$monday','$tuesday','$wednesday','$thursday','$friday','$saturday','$sunday','$time')";
    } else {
        // unreguler OR off trip query
        $sql = "INSERT INTO $tbl_name (`user_id`,`trip_Departure`, `departure_Longitude`, `departure_Latitude`, `trip_Destination`, `destination_Longitude`, `destination_Latitude`, `price`, `seats_Available`, `regular`, `date`, `time`) VALUES ('" . $_SESSION['user_id'] . "', '$departure','$departure_Longitude','$departure_Latitude','$trip_Destination','$destination_Longitude','$destination_Latitude','$price','$seats_Available','$regular','$date','$time')";
    }
    $results = mysqli_query($link, $sql);
    //To check if query run successful
    if (!$results) {
        echo '<div class=" alert alert-danger">There was an error! The trip could not be added to database!</div>';
    }
}

