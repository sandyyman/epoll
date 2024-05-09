<?php
session_start();
include 'config.php';
    $conn = mysqli_connect($db_host,$db_user,$db_password,$db_name);
    $error=" ";

    if(isset($_POST['update'])){
		$cid = $_POST['cid'];
		$cname = $_POST['cname'];
		$party = $_POST['party'];
		$const = $_POST['const'];
		$body = $_POST['body'];

		$sql = "UPDATE candidate SET cname = '$cname', party = '$party', const = '$const', body = '$body' WHERE cid = '$cid'";
		$res=mysqli_query($conn,$sql);
        mysqli_close($conn);
        
        header('Location: candidates.php');
    }
    if(!$conn){
        die("Connection Failed.");
    }
    else{
        echo "Connected Successfully!<br><br>";
    }
    $sql = "Select * from candidate";
    $result = mysqli_query($conn,$sql);
      
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
        table,th,td{
            text-align:center;
            padding: 10px;
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
    <?php

     $id=$_GET["cid"]; 

     if($conn){
        $sql="SELECT * FROM candidate WHERE cid='$id'";
        $res=mysqli_query($conn,$sql);
        if(mysqli_num_rows($res)>0)
        {
            $cand=mysqli_fetch_all($res,MYSQLI_ASSOC);
            $cname=$cand[0]['cname']; 
            $party=$cand[0]['party']; 
            $const=$cand[0]['const']; 
            $body=$cand[0]['body']; 
        }
    }
     ?>

    <div class="container" style="padding:100px 30px 20px 30px ;">
      <div class="row">
        <div class="col-sm-12" style="border:2px solid gray; padding:15px 300px 15px 300px">
        <center>
          <div class="page-header">
            <h2 class="specialHead" ></span>UPDATE CANDIDATE INFORMATION</h2><br>
            
            <form action="cupdatedata.php" method="POST" enctype="multipart/form-data">

      			<div class="form-group">
                    <label>Candidate ID *</label><br>
      				<input type="text" name="cid" value="<?php echo $id;  ?>"  placeholder="Enter Candidate ID eg.456" pattern="[0-9]{3}" class="form-control" required><br>
                      
                    <label>Candidate Full Name *</label><br>
      				<input type="text" name="cname" value="<?php echo $cname; ?>" placeholder="Enter Full Name" class="form-control" required><br>

                    <label>Political Party he/she belongs to *</label><br>
                      <input type="text" name="party" value="<?php echo $party; ?>" placeholder="Enter Political Party" class="form-control" required><br>
                    
                    <label>Constituency of the Candidate *</label><br>
      				<input type="text" name="const" value="<?php echo $const; ?>" placeholder="Enter Constituency" class="form-control" required><br>
                      
                    <label>Description</label><br>
                    <input type="text" name="body" value="<?php echo $body; ?>" placeholder="Enter few lines of Description" class="form-control"><br>
                      

                      <h5 style="color: red;"><?php echo $error; ?></h5><br>

      				<button type="submit" name="update" class="btn btn-block span btn-primary "><span class="glyphicon glyphicon-ppencil"></span>  UPDATE</button>

      			</div>

          </form>
            
          </div>
      </div>
    </div>
  </div>
  </center>
    <div class="container" style="padding:20px 30px 30px 30px;">
           
            <center>
            <button type="button" name="display" class="btn btn-info"><a href="candidates.php"><span class="glyphicon glyphicon-step-backward"></span>DISPLAY LIST</button>
          </center>
    </div>
      

