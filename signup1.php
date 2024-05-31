<?php 
session_start();
include 'config.php';
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$errors = array('name' => '', 'email' => '', 'age' => '', 'uname' => '', 'voter_id' => '', 'password' => '', 'cpassword' => '', 'voter_photo' => '');

if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $age = $_POST['age'];
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $voter_id = mysqli_real_escape_string($conn, $_POST['voter_id']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $valid = true;

    // Check if the username is already taken
    $sql = "SELECT * FROM voter WHERE uname = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $uname);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) > 0){
        $errors['uname'] = '*Username already taken by another user';
        $valid = false;
    }
    mysqli_stmt_close($stmt);

    // Check if the voter ID is valid and exists in govt_db table
    $sql = "SELECT * FROM govt_db WHERE voter_id = ? AND email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $voter_id, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) == 0){
        $errors['voter_id'] = '*Voter ID not found for this email';
        $valid = false;
    }
    mysqli_stmt_close($stmt);

    // Additional validation checks as per the original script
    if(!ctype_alpha(str_replace(' ', '', $name))){
        $errors['name'] = '*Full Name can contain only alphabets';
        $valid = false;
    }

    if($age <= 17){
        $errors['age'] = '*Age cannot be less than 18';
        $valid = false;
    }

    if(!ctype_alpha(str_replace(' ', '', $uname))){
        $errors['uname'] = '*Username can contain only alphabets and no blank spaces';
        $valid = false;
    }

    if(strlen($password) < 8 || strlen($password) > 50){
        $errors['password'] = '*Length must be between 8 and 50 characters';
        $valid = false;
    }

    if($password != $cpassword){
        $errors['cpassword'] = '*Passwords do not match';
        $valid = false;
    }

    $filename = $_FILES['voter_photo']['name'];
    if(empty($filename)){
        $errors['voter_photo'] = '*Please upload your passport photo';
        $valid = false;
    } else {
        move_uploaded_file($_FILES['voter_photo']['tmp_name'], 'images/'.$filename);
    }

    if($valid){
        $password = password_hash($password, PASSWORD_DEFAULT); // Encrypting the password
        $sql = "INSERT INTO voter (name, email, age, uname, voter_id, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssissss", $name, $email, $age, $uname, $voter_id, $password, $filename);
        mysqli_stmt_execute($stmt);
        if(mysqli_stmt_affected_rows($stmt) > 0){
            echo "<script>alert('Signup Successful! You can now login.');</script>";
        } else {
            echo "<script>alert('Signup failed, please try again!');</script>";
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>
