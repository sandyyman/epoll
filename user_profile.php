<?php
session_start();
include 'config.php';

// Establish database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Profile</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        img {
            border-radius: 15px;
        }
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
            <div class="container">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="navbar-header">
                    <a href="#" class="navbar-brand headerFont text-lg" style="color:rgb(112, 128, 144)"><strong>E-Poll</strong></a>
                </div>
            </div>
        </nav>

        <div class="container" style="padding: 100px;">
            <div class="row">
                <div class="col-sm-12" style="border: 2px solid gray;">
                    <center>
                        <div class="page-header">
                            <img src="./images/profile.png" alt="admin" width="200px" height="200px"><br><br>
                            <h2 class="specialHead">Welcome to Online Voting System, <?php echo htmlspecialchars($_SESSION["name"] ?? ''); ?>!</h2><br>
                            <span class="glyphicon glyphicon-user"></span>
                            <p class="subFont">
                                <?php if(isset($_SESSION["voter_id"])): ?>
                                    Voter ID: <?php echo htmlspecialchars($_SESSION["voter_id"]); ?><br>
                                <?php endif; ?>
                                Name: <?php echo htmlspecialchars($_SESSION["name"] ?? ''); ?><br>
                                Email ID: <?php echo htmlspecialchars($_SESSION["email"] ?? ''); ?><br>
                                Age: <?php echo htmlspecialchars($_SESSION["age"] ?? ''); ?><br>
                                Username: <?php echo htmlspecialchars($_SESSION["uname"] ?? ''); ?>
                            </p>

                            <br><br>

                            <form action="mail.php" method="POST">
                                <input type="submit" name="castvote" value="CAST YOUR VOTE!" class="btn btn-primary"><br><br>
                            </form>
                            <form action="logout.php" method="POST">
                                <input type="submit" name="logout" value="LOGOUT" class="btn btn-success">
                            </form>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
