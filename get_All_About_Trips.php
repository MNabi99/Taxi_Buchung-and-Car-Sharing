<?php
//start session and connection
session_start();
include('my_Connection.php');

//retrieve trips one by one to check Availability
$sql="SELECT * FROM Taxi_Buchung WHERE user_id='".$_SESSION['user_id']."'";

if($result = mysqli_query($link, $sql)){
    //print_r($result);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            //check frequency
            if($row['regular']=="N"){
                $frequency = "One-off journey.";
                $time = $row['date']." at " .$row['time'].".";
            }else{
                $frequency = "Regular."; 
                $array = [];
                    if($row['monday']==1){array_push($array,"Mon");}
                    if($row['tuesday']==1){array_push($array,"Tue");}
                    if($row['wednesday']==1){array_push($array,"Wed");}
                    if($row['thursday']==1){array_push($array,"Thu");}
                    if($row['friday']==1){array_push($array,"Fri");}
                    if($row['saturday']==1){array_push($array,"Sat");}
                    if($row['sunday']==1){array_push($array,"Sun");}
                $time = implode("-", $array)." at " .$row['time'].".";
            } 
            echo 
             '<div class="row trip">
                    <div class="col-sm-8 journey">
                        <div><span class="departure">Abreise:</span> '.$row['departure'].'.</div>
                        <div><span class="destination">Reiseziel:</span> '. $row['destination'] .'.</div>
                        <div class="time">'.$time.'</div>
                        <div>'.$frequency.'</div>
                    </div>
                    <div class="col-sm-2 price">
                        <div class="price">$'.$row['price'].'</div>
                        <div class="perseat">Pro Sitzplatz</div>
                        <div class="seatsavailable">'.$row['seatsavailable'].' left</div>
                    </div>
                    <div class="col-sm-2">
                        <button class= "btn green edit btn-lg" data-target="#edittripModal" data-toggle="modal" data-trip_id="'.$row['trip_id'].'">Bearbeiten:</button>
                    </div>
                </div>';
        }
    }else{ 
        echo '<div class="notrips alert alert-warning">Sie haben noch keine Reisen erstellt</div>';
    }
}
