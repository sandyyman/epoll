<?php 
session_start();
include 'config.php';
$conn = mysqli_connect($db_host,$db_user,$db_password,$db_name);
if(!$conn)
{
    die("Connection Failed");
}
$errors = array('name' => '', 'email' => '', 'age' => '', 'uname' => '', 'voter_id' => '', 'password' => '', 'cpassword' => '', 'flag' => '', 'voter_photo' => '');

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $uname = $_POST['uname'];
    $voter_id = $_POST['voter_id'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $flag = false;
    $active = 0;
    $valid = true;
    
    // Check if the username is already taken
    $sql = "SELECT * FROM voter WHERE uname='$uname'";
    $res = mysqli_query($conn, $sql);
    if(mysqli_num_rows($res) > 0){
        $errors['uname'] = '*Name already taken by another user';
        $valid = false;
    }

    // Check if the voter ID is valid and exists in govt_db table
    $sql = "SELECT * FROM govt_db WHERE voter_id='$voter_id' AND email='$email'";
    $res = mysqli_query($conn, $sql);
    if(mysqli_num_rows($res) == 0){
        $errors['voter_id'] = '*Voter ID not found for this email';
        $valid = false;
    }

    // Validate name
    if(!ctype_alpha(str_replace(' ', '', $name))){
        $errors['name'] = '*Full Name can contain only alphabets';
        $valid = false;
    }

    // Validate age
    if($age <= 17){
        $errors['age'] = '*Age cannot be less than 18';
        $valid = false;
    }

    // Validate username
    if(!ctype_alpha(str_replace(' ', '', $uname))){
        $errors['uname'] = '*Username can contain only alphabets and no blank spaces';
        $valid = false;
    }

    // Validate password
    if(strlen($password) < 8 || strlen($password) > 50){
        $errors['password'] = '*Length must be between 8 and 50 characters';
        $valid = false;
    }

    // Confirm password
    if($password != $cpassword){
        $errors['cpassword'] = '*Passwords do not match';
        $valid = false;
    }

    // Image upload
    $filename = $_FILES['voter_photo']['name'];
    if(empty($filename)){
        $errors['voter_photo'] = '*Please upload your passport photo';
        $valid = false;
    } else {
        move_uploaded_file($_FILES['voter_photo']['tmp_name'], 'images/'.$filename);
    }

    // If all validations pass, proceed with signup
    if($valid){
        // Insert user data into the voter table
        $msg = 'Your account has been created. Please verify it by clicking the activation link that has been sent to your email.';
        $hash = md5(rand(0,1000)); // Generate random 32 character hash
        $sql = "INSERT INTO voter VALUES('$name','$email','$age','$uname','$voter_id','$password','$flag','$hash','$active','$filename')";
        $res = mysqli_query($conn, $sql);  

        // Send verification email
        $message = 'Thanks for signing up! Your account has been created, you can login with the following credentials after you have activated your account by clicking the URL below.';
        $message .= 'Username: '.$name;
        $message .= 'Please click this link to activate your account: http://localhost/onlinevoting/verify.php?email='.$email.'&hash='.$hash;

        require_once('./PHPMailer/PHPMailerAutoload.php');
        $from = 'epollad01@gmail.com';
        $to = $email;
        $password = 'epolladmin008';
        $sub = 'Signup | Verification';
        $body = $message;

        $mail = new PHPMailer(); 
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->isHTML();
        $mail->Username = $from;
        $mail->Password = $password;
        $mail->Subject = $sub;
        $mail->Body = $body;
        $mail->AddAddress($to);

        if($mail->Send()){
            echo "<script>alert('Email Sent Successfully!');</script>";
        } else {
            echo "<script>alert('There has been an error, please try again!');</script>";
        }

        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
            <div class="navbar-header">
                <a href="index.html" class="navbar-brand headerFont text-lg" style="color:rgb(169, 208, 240)"><strong>ePoll</strong></a>
            </div>
        </nav>

        <div class="container" style="padding-top:150px;">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4" style="border:2px solid gray;padding:50px;">

                    <div class="page-header">
                        <h2 class="specialHead">Sign up</h2>
                    </div>
                    
                    <form action="signup.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Full Name:</label>
                            <input type="text" name="name" placeholder="Full Name" class="form-control" value="<?php echo isset($_POST["name"]) ? $_POST["name"] : ''; ?>" required>
                            <div style="color: red;"><?php echo $errors['name']; ?></div><br><br>

                            <label>Age:</label>
                            <input type="number" name="age" class="form-control" value="<?php echo isset($_POST["age"]) ? $_POST["age"] : ''; ?>" required>
                            <div style="color: red;"><?php echo $errors['age']; ?></div><br><br>

                            <label>Username:</label>
                            <input type="text" name="uname" class="form-control" placeholder="Only alphabets" value="<?php echo isset($_POST["uname"]) ? $_POST["uname"] : ''; ?>" required>
                            <div style="color: red;"><?php echo $errors['uname']; ?></div><br><br>

                            <label>Email Id:</label>
                            <input type="email" name="email" class="form-control" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : ''; ?>" required>
                            <div style="color: red;"><?php echo $errors['email']; ?></div><br><br>

                            <label>Voter ID:</label>
                            <input type="text" name="voter_id" class="form-control" placeholder="7 digits" value="<?php echo isset($_POST["voter_id"]) ? $_POST["voter_id"] : ''; ?>" pattern="[0-9]{7}" title="Please enter a 7-digit numeric voter ID" required>
                            <div style="color: red;"><?php echo $errors['voter_id']; ?></div><br><br>

                            <label>Select image to upload:</label><br> 
                            <input type="file" name="voter_photo" id="fileToUpload"><br><br>
                            
                            <label>Password:</label>
                            <input type="password" name="password" class="form-control" placeholder="8-50 characters" required>
                            <div style="color: red;"><?php echo $errors['password']; ?></div><br><br>

                            <label>Confirm Password:</label>
                            <input type="password" name="cpassword" class="form-control" required>
                            <div style="color: red;"><?php echo $errors['cpassword']; ?></div><br><br>

                            <button type="submit" name="submit" class="btn btn-block span btn-success "><span class="glyphicon glyphicon-user"></span> Sign In</button
