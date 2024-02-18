<?php
session_start();
include('my_Connection.php');

//logout
include('logout.php');

//remember me
include('remember_User_Data.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Taxi Auto</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="styling.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/sunny/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script
    src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyCwJ 2Vepe9L2Miuh7QH87SR_RItIXHlX6Q"></script>
  <style>
    /*margin top for myContainer*/
    #myContainer {
      margin-top: 50px;
      text-align: center;
      color: black;
    }

    /*header size*/
    #myContainer h1 {
      font-size: 5em;
    }

    .bold {
      font-weight: bold;
    }

    #googleMap {
      width: 100%;
      height: 30vh;
      margin: 10px auto;
    }

    .signup {
      margin-top: 20px;
    }

    #spinner {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      height: 85px;
      text-align: center;
      margin: auto;
      z-index: 1100;
    }

    #results {
      margin-bottom: 100px;
    }

    .driver {
      font-size: 1.5em;
      text-transform: capitalize;
      text-align: center;
    }

    .price {
      font-size: 1.5em;
    }

    .departure,
    .destination {
      font-size: 1.5em;
    }

    .perseat {
      font-size: 0.5em;
    }

    .journey {
      text-align: left;
    }

    .journey2 {
      text-align: right;
    }

    .time {
      margin-top: 10px;
    }

    .telephone {
      margin-top: 10px;
    }

    .seatsavailable {
      font-size: 0.7em;
      margin-top: 5px;
    }

    .moreinfo {
      text-align: left;
    }

    .aboutme {
      border-top: 1px solid grey;
      margin-top: 15px;
      padding-top: 5px;
    }

    #message {
      margin-top: 20px;
    }

    .journeysummary {
      text-align: left;
      font-size: 1.5em;
    }

    .noresults {
      text-align: center;
      font-size: 1.5em;
    }

    .previewing {
      max-width: 100%;
      height: auto;
      border-radius: 50%;
    }

    .previewing2 {
      margin: auto;
      height: 20px;
      border-radius: 50%;
    }
  </style>
</head>

<body>
  <!--Navigation Bar-->
  <?php
  if (isset($_SESSION["user_id"])) {
    include("navigation_Bar_Connected.php");
  } else {
    include("navigation_Bar_Not_Connected.php");
  }
  ?>

  <div class="container-fluid" id="myContainer">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <h1>Planen Sie jetzt Ihre nächste Reise!</h1>
        <p class="lead">Sparen Sie Zeit! Geld Sie sparen! </p>
        <p class="bold">Einfach kommen, einfach gehen! </p>
        <!--Search Form-->
        <form class="form-inline" method="get" id="searchform">
          <div class="form-group">
            <label class="sr-only" for="departure">Abreise:</label>
            <input type="text" class="form-control" id="departure" placeholder="Abreise" name="departure">
          </div>
          <div class="form-group">
            <label class="sr-only" for="departure">Bestimmungsort:</label>
            <input type="text" class="form-control" id="destination" placeholder="Bestimmungsort" name="destination">
          </div>
          <input type="submit" value="Search" class="btn btn-lg green" name="search">

        </form>
        <!--Search Form End-->

        <!--Google Map        -->
        <div id="googleMap"></div>

        <!--Sign up button    -->
        <?php
        if (!isset($_SESSION["user_id"])) {
          echo '<button type="button" class="btn btn-lg green signup" data-toggle="modal" data-target="#signupModal">Melden Sie sich an - es ist kostenlos.</button>';
        }
        ?>
        <div id="results">
          <!--An Ajax Call could run this-->
        </div>

      </div>

    </div>

  </div>

  <!--Login form-->
  <form method="post" id="login_Form">
    <div class="modal" id="loginModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 id="myModalLabel">
              Anmelden:
            </h4>
          </div>
          <div class="modal-body">
            <!-- sending Login message -->
            <div id="loginmessage"></div>
            <div class="form-group">
              <label for="loginemail" class="sr-only">Email:</label>
              <input class="form-control" type="email" name="loginemail" id="loginemail" placeholder="Email"
                maxlength="50">
            </div>
            <div class="form-group">
              <label for="loginpassword" class="sr-only">Kennwort</label>
              <input class="form-control" type="password" name="loginpassword" id="loginpassword" placeholder="Password"
                maxlength="30">
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="rememberme" id="rememberme">
                Erinnere mich
              </label>
              <a class="pull-right" style="cursor: pointer" data-dismiss="modal" data-target="#forget_Password_Model"
                data-toggle="modal">
                Passwort vergessen?
              </a>
            </div>
          </div>
          <div class="modal-footer">
            <input class="btn green" name="login" type="submit" value="Login">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Abbrechen
            </button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="signupModal"
              data-toggle="modal">
              Registrieren
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!--Sign up form-->
  <form method="post" id="signupform">
    <div class="modal" id="signupModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 id="myModalLabel">
              Online-App starten
            </h4>
          </div>
          <div class="modal-body">

            <!--Sign up message-->
            <div id="signupmessage"></div>
            <div class="form-group">
              <label for="username" class="sr-only">Benutzer:</label>
              <input class="form-control" type="text" name="username" id="username" placeholder="Username"
                maxlength="30">
            </div>
            <div class="form-group">
              <label for="firstname" class="sr-only">Vorname:</label>
              <input class="form-control" type="text" name="firstname" id="firstname" placeholder="Firstname"
                maxlength="30">
            </div>
            <div class="form-group">
              <label for="lastname" class="sr-only">Nachname:</label>
              <input class="form-control" type="text" name="lastname" id="lastname" placeholder="Lastname"
                maxlength="30">
            </div>
            <div class="form-group">
              <label for="email" class="sr-only">Email:</label>
              <input class="form-control" type="email" name="email" id="email" placeholder="Email Address"
                maxlength="50">
            </div>
            <div class="form-group">
              <label for="password" class="sr-only">Kennwort:</label>
              <input class="form-control" type="password" name="password" id="password" placeholder="Choose a password"
                maxlength="30">
            </div>
            <div class="form-group">
              <label for="password2" class="sr-only">Kennwort bestätigen</label>
              <input class="form-control" type="password" name="password2" id="password2" placeholder="Confirm password"
                maxlength="30">
            </div>
            <div class="form-group">
              <label for="phonenumber" class="sr-only">Telephone:</label>
              <input class="form-control" type="text" name="phonenumber" id="phonenumber" placeholder="Telephone Number"
                maxlength="15">
            </div>
            <div class="form-group">
              <label><input type="radio" name="gender" id="male" value="male">Männlich</label>
              <label><input type="radio" name="gender" id="female" value="female">Weiblich</label>
            </div>
            <div class="form-group">
              <label for="moreinformation">Kommentare: </label>
              <textarea name="moreinformation" class="form-control" rows="5" maxlength="300"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <input class="btn green" name="signup" type="submit" value="Sign up">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Abbrechen
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!--Forgot password form-->
  <form method="post" id="forget_Password_Form">
    <div class="modal" id="forget_Password_Model" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 id="myModalLabel">
              Passwort vergessen? Geben Sie Ihre E-Mail Adresse ein:
            </h4>
          </div>
          <div class="modal-body">
            <!--forgot password message file-->
            <div id="forget_password_message"></div>
            <div class="form-group">
              <label for="forget_Email" class="sr-only">Email:</label>
              <input class="form-control" type="email" name="forget_Email" id="forget_Email" placeholder="Email"
                maxlength="50">
            </div>
          </div>
          <div class="modal-footer">
            <input class="btn green" name="forgotpassword" type="submit" value="Submit">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Abbrechen
            </button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="signupModal"
              data-toggle="modal">
              Registrieren
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!-- Footer-->
  <div class="footer">
    <div class="container">
      <p>Soft Outlet Metzingen Copyright &copy; 2024-
        <?php $today = date("Y");
        echo $today ?>
      </p>
    </div>
  </div>

  <!--Spinner-->
  <div id="spinner">
    <img src='ajax-loader.gif' width="64" height="64" />
    <br>Loading..
  </div>

  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="js/bootstrap.min.js"></script>
  <script src="map.js"></script>
  <script src="javascript.js"></script>
</body>

</html>