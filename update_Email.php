<?php
//session and connection
session_start();
include ('my_Connection.php');

//get user_id and new email sent through Ajax
$user_id = $_SESSION['user_id'];
$newemail = $_POST['email'];

//if new email exists
$sql = "SELECT * FROM users WHERE email='$newemail'";
$result = mysqli_query($link, $sql);
$count = $count = mysqli_num_rows($result);
if($count>0){
    echo "<div class='alert alert-danger'>Es ist bereits ein Benutzer mit dieser E-Mail-Adresse registriert! Bitte wählen Sie einen anderen!</div>"; exit;
}


//get the current email
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);

$count = mysqli_num_rows($result);

if($count == 1){
    $row = mysqli_fetch_array($result, MYSQL_ASSOC); 
    $email = $row['email']; 
}else{
    echo "<div class='alert alert-danger'> error </div>";exit;   
}

//create a unique activation code
$activationKey = bin2hex(openssl_random_pseudo_bytes(16));

//insert new activation code in the users table
$sql = "UPDATE users SET activation2='$activationKey' WHERE user_id = '$user_id'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo "<div class='alert alert-danger'>Error Msg.</div>";exit;
}else{
    //send email with link to activatenewemail.php with current email, new email and activation code
    $message = "Bitte klicken Sie auf diesen Link, dass Sie diese E-Mail besitzen:\n\n";
$message .= "http://taxibuchung.hosting.com/activate_your_new_email.php?email=" . urlencode($email) . "&newemail=" . urlencode($newemail) . "&key=$activationKey";
if(mail($newemail, 'Email Update for you Online Notes App', $message, 'From:'.'taxi_buchung')){
       echo "<div class='alert alert-success'>Es wurde eine E-Mail an $newemail gesendet. Bitte klicken Sie auf den Link, um zu beweisen, dass Sie diese E-Mail-Adresse besitzen.</div>";
}
    
}


?>