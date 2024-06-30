<?php
session_start();
include 'config.php';
$conn = mysqli_connect($db_host,$db_user,$db_password,$db_name);
$error = " ";
$flag = 1;

if(isset($_POST['insert'])){
    $voter_id = $_POST['voter_id'];
    $email = $_POST['email'];

    // Check if the voter ID or email already exists in govt_db
    $sql_check = "SELECT * FROM govt_db WHERE voter_id='$voter_id' OR email='$email'";
    $result_check = mysqli_query($conn, $sql_check);
    $rows_check = mysqli_num_rows($result_check);
    
    if($rows_check > 0){
        $error = "ERROR: Voter ID or Email already exists!";
    } else {
        // Insert the voter data into the govt_db table
        $sql_insert = "INSERT INTO govt_db (voter_id, email) VALUES ('$voter_id', '$email')";
        if(mysqli_query($conn, $sql_insert)){
            // Redirection to the voters list page after successful insertion
            header('Location: adminlanding.php');
            exit(); 
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

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
    <title>Add Voter</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
    <style>
        table, th, td {
            text-align:center;
            padding: 10px;
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
            <div class="navbar-header">
                <!-- logo here -->
                <a href="#" class="navbar-brand headerFont text-lg" style="color:rgb(112, 128, 144)"><strong>E-Poll</strong></a>
            </div>
            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="example-nav-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="adminlanding.php"><span class="subFont"><strong>Admin Info</strong></span></a></li>
                    <li><a href="candidate.php"><span class="subFont"><strong>Candidates List</strong></span></a></li>
                    <li><a href="statistics.php"><span class="subFont"><strong>Statistics</strong></span></a></li>
                </ul>
                <!-- Logout button -->
                <span class="normalFont"><a href="index.html" class="btn btn-danger navbar-right navbar-btn"><strong>Log Out</strong></a></span></button>
            </div>
        </div>
    </nav>
    <div class="container" style="padding:100px 30px 20px 30px ;">
        <div class="row">
            <div class="col-sm-12" style="border:2px solid gray; padding:15px 300px 15px 300px">
                <center>
                    <div class="page-header">
                        <h2 class="specialHead">INSERT NEW VOTER</h2><br>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <h5 style="color: red;"><?php echo $error; ?></h5><br>
                                <label>Voter ID *</label><br>
                                <input type="text" name="voter_id" placeholder="Enter Voter ID" class="form-control" required><br>
                                <label>Email *</label><br>
                                <input type="email" name="email" placeholder="Enter Email" class="form-control" required><br>
                                <button type="submit" name="insert" class="btn btn-block span btn-primary "><span class="glyphicon glyphicon-plus"></span>  INSERT</button>
                            </div>
                        </form>
                    </div>
                </center>
            </div>
        </div>
    </div>
    <div class="container" style="padding:20px 30px 30px 30px;">
        
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
