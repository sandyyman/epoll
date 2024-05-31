<?php
session_start();
include 'config.php';

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
$error = '';

// Registration Process
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $age = (int)$_POST['age'];
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $voter_id = mysqli_real_escape_string($conn, $_POST['voter_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $flag = mysqli_real_escape_string($conn, $_POST['flag']);
    $hash = mysqli_real_escape_string($conn, $_POST['hash']);
    $active = (int)$_POST['active'];
    $voter_photo = mysqli_real_escape_string($conn, $_POST['voter_photo']); // Assuming it's a file path

    $sql = "INSERT INTO voter (name, email, age, uname, voter_id, password, flag, hash, active, voter_photo) 
            VALUES ('$name', '$email', $age, '$uname', '$voter_id', '$password', '$flag', '$hash', $active, '$voter_photo')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to login page after successful registration
        header("Location: login.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Page</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Include other CSS files if needed -->
</head>
<body>
<div class="container">
  	<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
        <!-- Your navigation bar content -->
    </nav>
    
    <div class="container" style="padding-top:150px;">
    	<div class="row">
    		<div class="col-sm-4"></div>
    		<div class="col-sm-4" style="border:2px solid black;padding:50px; background-color:rgb(112, 128, 144)">
    			
    			<div class="page-header">
    				<h2 class="specialHead">Register</h2>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" class="form-control" required><br>

                        <label>Email:</label>
                        <input type="email" name="email" class="form-control" required><br>

                        <label>Age:</label>
                        <input type="number" name="age" class="form-control" required><br>

                        <label>Username:</label>
                        <input type="text" name="uname" class="form-control" required><br>

                        <label>Voter ID:</label>
                        <input type="text" name="voter_id" class="form-control" required><br>

                        <label>Password:</label>
                        <input type="password" name="password" class="form-control" required><br>

                        <!-- Additional fields for registration -->
                        <!-- Modify according to your needs -->
                        <input type="hidden" name="flag" value="some_value">
                        <input type="hidden" name="hash" value="some_value">
                        <input type="hidden" name="active" value="1">
                        <input type="hidden" name="voter_photo" value="path_to_photo.jpg">

                        <h5 style="color: red;"><?php echo $error; ?></h5>

                        <button type="submit" name="submit_register" class="btn btn-block btn-success">Register</button>
                    </div>
                </form>
          </div>
          <div class="col-sm-4"></div>
      </div>
  </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
