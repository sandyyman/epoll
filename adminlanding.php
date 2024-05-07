<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Control Panel</title>

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

        <div class="collapse navbar-collapse" id="example-nav-collapse">
          <ul class="nav navbar-nav">
          <li><a href="adminlanding.php"><span class="subFont"><strong>Admin Info</strong></span></a></li>
             <li><a href="candidates.php"><span class="subFont"><strong>Candidates List</strong></span></a></li>
            <li><a href="statistics.php"><span class="subFont"><strong>Statistics</strong></span></a></li>        
          </ul>
          
          <span class="normalFont"><a href="index.html" class="btn btn-danger navbar-right navbar-btn"><strong>Log Out</strong></a></span></button>
        </div>

      </div>
    </nav>

    <div class="container" style="padding:100px;">
      <div class="row">
        <div class="col-sm-12" style="border:2px solid gray;">
          <center>
          <div class="page-header">
            <img src="images/GitHub.png" alt="admin" width="250px" height="250px"><br><br>
            <h2 class="specialHead" >Welcome to ePoll ADMIN PANEL,  <?php echo $_SESSION["fname"] . "!<br>";?></h2><br><br>
            <span class="glyphicon glyphicon-user"></span>
            <p class="normalFont"><?php  echo "Admin ID : " . $_SESSION["aid"] . "<br>";
                                        echo "User Name : " . $_SESSION["aname"] . ""; ?></p>
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
