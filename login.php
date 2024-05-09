<?php 
session_start();
include 'config.php';
require_once "./PHPMailer/src/PHPMailer.php";
require_once "./PHPMailer/src/SMTP.php";
require_once "./PHPMailer/src/Exception.php";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
$success = "";
$error_message = "";
$error = '';

if (isset($_POST['submit_email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($conn) {
        $sql = "SELECT * FROM voter WHERE email='$email' AND password='$password'";
        $res = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($res) > 0) {
            $voters = mysqli_fetch_all($res, MYSQLI_ASSOC);
            $active = $voters[0]['active'];

            if ($active == 1) {
                $_SESSION['name'] = $voters[0]['name'];
                $_SESSION['email'] = $voters[0]['email'];
                $_SESSION['age'] = $voters[0]['age'];
                $_SESSION['voter_id'] = $voters[0]['voter_id'];
                $_SESSION['uname'] = $voters[0]['uname'];
                $_SESSION['password'] = $password;
                $_SESSION['voter_photo'] = $voters[0]['voter_photo'];
                
                // Generate OTP
                $otp = rand(100000, 999999);
                
                // Send OTP
                require_once("mail_function.php");
                $mail_status = sendOTP($email, $otp);
                
                if ($mail_status) {
                    $result = mysqli_query($conn, "INSERT INTO otp_expiry(otp, is_expired, create_at) VALUES ('" . $otp . "', 0, '" . date("Y-m-d H:i:s") . "')");
                    $current_id = mysqli_insert_id($conn);
                    
                    if (!empty($current_id)) {
                        $success = 1;
                    }
                } else {
                    $error_message = "Failed to send OTP. Please try again later.";
                }
            } else {
                echo 'Account is not activated';
            }
        } else {
            $error = '*Incorrect id/password';
        }
    }
}

if (isset($_POST["submit_otp"])) {
    $result = mysqli_query($conn, "SELECT * FROM otp_expiry WHERE otp='" . $_POST["otp"] . "' AND is_expired!=1 AND NOW() <= DATE_ADD(create_at, INTERVAL 24 HOUR)");
    $count = mysqli_num_rows($result);

    if (!empty($count)) {
        $result = mysqli_query($conn, "UPDATE otp_expiry SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "'");
        $success = 2;
    } else {
        $success = 1;
        $error_message = "Invalid OTP!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
    <style>
      .headerFont {
        font-family: 'Ubuntu', sans-serif;
        font-size: 24px;
      }
      .subFont {
        font-family: 'Raleway', sans-serif;
        font-size: 14px;
      }
      .specialHead {
        font-family: 'Oswald', sans-serif;
      }
      .normalFont {
        font-family: 'Roboto Condensed', sans-serif;
      }
    </style>
</head>
<body>
<div class="container">
  	<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
        <div class="navbar-header">
          <a href="index.html" class="navbar-brand headerFont text-lg" style="color:rgb(112, 128, 144)"><strong>E-Poll</strong></a>
      </div>
    </nav>
    
    <div class="container" style="padding-top:150px; ">
    	<div class="row" >
    		<div class="col-sm-4"></div>
    		<div class="col-sm-4" style="border:2px solid black;padding:50px; background-color:rgb(112, 128, 144)">
    			
    			<div class="page-header">
    				<h2 class="specialHead">Log In</h2>
                </div>
                <form method="post" action="login.php">
                    <div class="form-group">
                      <?php 
                        if (!empty($success == 1)) { 
                      ?>
                      <label>Enter OTP</label>
                      <p style="color:#31ab00;">Check your email for the OTP</p>
                      <input type="text" name="otp"  class="form-control" required><br><br>
                      
                      <h5 style="color: red;"><?php echo $error_message; ?></h5>

                      <button type="submit" name="submit_otp" class="btn btn-block span btn-primary "><span class="glyphicon glyphicon-OK"></span> Submit OTP</button>  
                      <?php 
                        } else if ($success == 2) {
                          header("location: user_profile.php");
                          ?>
                      <?php
                        }
                        else {
                      ?>
                      <label>Email:</label>
                    <input type="email" name="email" class="form-control" required><br><br>

                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required><br><br>

                    <h5 style="color: red;"><?php echo $error; ?></h5>

      				      <button type="submit" name="submit_email" class="btn btn-block span btn-success ">Log In</button>
                    <br><a href="signup.php" class="form-control btn-danger">New User? SignUp</a>
                      <?php 
                        }
                      ?>
                    </div>
                  </form>      
        
          <br>

    		</div>
    		<div class="col-sm-4"></div>
    	</div>
    </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
