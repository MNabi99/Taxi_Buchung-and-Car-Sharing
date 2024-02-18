<?php
session_start();
//If the user is not logged in & rememberme cookie exists
if(!isset($_SESSION['user_id']) && !empty($_COOKIE['rememberme'])){
           array_key_exists('user_id', $_SESSION);
        // method_Funtion_1: COOKIE: $a . "," . bin2hex($b);
        // method_Funtion_2: hash('sha256', $a);
        //  f1 = method_Funtion_1
        //  f2 = method_Funtion_2

    $cookieValue = method_Funtion_1($authentificator_Code_1, $authentificator_Code_2);
    // extract $authentificators 1&2 from the cookie
    list($authentificator_Code_1, $authentificator_Code_2) = explode(',', $_COOKIE['rememberme']);
    $authentificator_Code_2 = hex2bin($authentificator_Code_2);
    $f2authentificator_Code_2 = hash('sha256', $authentificator_Code_2);
    
    //Look for authentificator1 in the rememberme table
    $sql = "SELECT * FROM rememberme where authentificator_Code_1 = '$authentificator_Code_1'";
    $result = mysqli_query($link, $sql);
    if(!$result){
        echo  '<div class="alert alert-danger"> error .</div>'; 
        exit;
    }
    $count = mysqli_num_rows($result);
    if($count !== 1){
        echo '<div class="alert alert-danger">Remember me process failed!</div>';
        exit;
    }
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    //if authentificator2 does not match
    if(!hash_equals($row['f2authentificator2'], $f2authentificator2)){
        echo '<div class="alert alert-danger">hash_equals returned false.</div>';
    }else{
        //generate new authentificators
        //Store them in cookie and rememberme table
        $authentificator1 = bin2hex(openssl_random_pseudo_bytes(10));
        //2*2*...*2
        $authentificator2 = openssl_random_pseudo_bytes(20);
        //Store them in a cookie
        function f1($a, $b){
            $c = $a . "," . bin2hex($b);
            return $c;
        }
        $cookieValue = f1($authentificator1, $authentificator2);
        setcookie(
            "rememberme",
            $cookieValue,
            time() + 1296000
        );
        
        //Run query to store them in rememberme table
        function f2($a){
            $b = hash('sha256', $a); 
            return $b;
        }
        $f2authentificator2 = f2($authentificator2);
        $user_id = $_SESSION['user_id'];
        $expiration = date('Y-m-d H:i:s', time() + 1296000);
        
        $sql = "INSERT INTO rememberme
        (`authentificator1`, `f2authentificator2`, `user_id`, `expires`)
        VALUES
        ('$authentificator1', '$f2authentificator2', '$user_id', '$expiration')";
        $result = mysqli_query($link, $sql);
        if(!$result){
            echo  '<div class="alert alert-danger"> Error Try it next time.</div>';  
        }
        
        //Log the user in and redirect to notes page
        $_SESSION['user_id'] = $row['user_id'];
        header("location:main_Page_Loggedin.php");
        
    }
}
