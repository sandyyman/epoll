<?php
session_start();
include 'config.php';
    $conn = mysqli_connect($db_host,$db_user,$db_password,$db_name);
    if(!$conn){
        die("Connection Failed.");
    }    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>user profile</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>

    <style>
      img {
        border-radius: 15px;
      }
      .headerFont{
        font-family: 'Ubuntu', sans-serif;
        font-size: 24px;
      }

      .subFont{
        font-family: 'Raleway', sans-serif;
        font-size: 14px;
        
      }
      
      .specialHead{
        font-family: 'Oswald', sans-serif;
      }

      .normalFont{
        font-family: 'Roboto Condensed', sans-serif;
      }
    </style>

  </head>
  <body>
    
  <div class="container">
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse
    " role="navigation">
      <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <div class="navbar-header">
          <a href="#" class="navbar-brand headerFont text-lg" style="color:rgb(169, 208, 240)"><strong>ePoll</strong></a>
        </div>
        </div>

      </div> 
    </nav>


    <div class="container" style="padding:100px;">
      <div class="row">
        <div class="col-sm-12" style="border:2px solid gray;">
          <center>
          <div class="page-header">
            <img src="images/HowTo.png" alt="admin" width="200px" height="200px"><br><br>
            <h2 class="specialHead" >Welcome to Online Voting System ,  <?php echo $_SESSION["name"] . "!<br>";?></h2><br>
            <span class="glyphicon glyphicon-user"></span>
            <p class="subFont"><?php  echo "Voter ID : " . $_SESSION["voter_id"] . "<br>";
                                        echo "Name : " . $_SESSION["name"] . "<br>";
                                        echo "Email ID : " . $_SESSION["email"] . "<br>";
                                        echo "Age : " . $_SESSION["age"] . "<br>";
                                        echo "UserName : " . $_SESSION["uname"] . ""; ?></p>

            <br><br>

            <?php
                if(isset($_POST['castvote']))
                {
                $voter_id = $_SESSION['voter_id'];
                $sql="SELECT * FROM voter WHERE voter_id = '$voter_id' ";
                $result= mysqli_query($conn,$sql);
                $voter_info=mysqli_fetch_all($result,MYSQLI_ASSOC);
                $flag= $voter_info[0]['flag'];
                if($flag == 0)
                {
                    //header("location: vote.php");
                    header("location: webcam.php");
                }
                else{
                    $nam = $_COOKIE['voterName'];
                    echo "<script>alert('You have already voted once $nam !');</script>"; 
                }          
                }


                if(isset($_POST['logout']))
                {
                session_unset();
                session_destroy();
                header("location: index.html");
                }  
        ?>


            <form action="user_profile.php" method="POST">
            <input type="submit" name="castvote" value="CASTE YOUR VOTE!" class="btn btn-primary"><br><br>
            </form>
            <form action="user_profile.php" method="POST">
            <input type="submit" name="logout" value="LOGOUT" class="btn btn-success">
            </form>
          </center>
          </div>
          
          </div>

        </div>
      </div>
    </div>
  </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
