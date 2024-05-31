<?php  
session_start();
include 'config.php';
  $conn = mysqli_connect($db_host,$db_user,$db_password,$db_name);
    $error="";

    if(!$conn){
        die("Connection Failed.");
        }
        else{
        echo "Connected Successfully!<br><br>";
        }

  if(isset($_POST['submit']))
  {
    $aname=$_POST['adminUserName'];
    $apwd=$_POST['adminPassword'];
    $fname=$_POST['adminName'];
    
    if($conn){
        $sql="SELECT * FROM admin WHERE aname='$aname' AND apwd='$apwd' ";
        $res=mysqli_query($conn,$sql);
        if(mysqli_num_rows($res)>0)
        {
            $admin=mysqli_fetch_all($res,MYSQLI_ASSOC); //returns all the data fetched as an associative array
            $_SESSION['aname'] = $aname;
            $_SESSION['fname'] = $fname;
            $_SESSION['aid']=$admin[0]['aid'];
            $_SESSION['apwd']=$apwd;
            header("location: adminlanding.php");
        }
        else
        {
            $error='*Incorrect Login ID or password';
        }
    }

  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>

    <style>
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

      .link-create-admin {
      display: block;
      text-align: center;
      margin-top: 20px;
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

    
    <div class="container" style="padding-top:150px;">
    	<div class="row">
    		<div class="col-sm-4"></div>
    		<div class="col-sm-4" style="border:2px solid gray;padding:50px; background-color:rgb(112, 128, 144)">
    			
    			<div class="page-header">
    				<h2 class="specialHead">Admin Login</h2>
                </div>
                
          <form action="admin.php" method="POST">
      			<div class="form-group">
              <label>Admin Full Name</label><br>
      				<input type="text" name="adminName" placeholder="Enter Full Name" class="form-control" required><br>
                      
              <label>Admin Username</label><br>
      				<input type="text" name="adminUserName" placeholder="Enter Admin's UserName" class="form-control" required><br>

      				<label>Admin Password</label><br>
      				<input type="password" name="adminPassword" class="form-control" placeholder="Enter Admin's Password" required><br>

              <h5 style="color: red;"><?php echo $error; ?></h5><br>

      				<button type="submit" name="submit" class="btn btn-block span btn-success btn-primary ">Sign In</button>

              <a href="createAdmin.php" class="link-create-admin btn-danger ">Create New Admin</a>

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