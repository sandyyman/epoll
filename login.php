<?php
session_start();
include 'config.php';

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = '';

// Login Process
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT voter_id, name, age, uname, email, password FROM voter WHERE email='$email'";
    $res = mysqli_query($conn, $sql);
    // $sql contains the query, $res will store set object for select statement and for insert, delete etc stored true or false
    if (mysqli_num_rows($res) > 0) {
        $voter = mysqli_fetch_assoc($res);
        // Compare plaintext passwords
        if ($password === $voter['password']) {
            // Set all session variables
            $_SESSION['voter_id'] = $voter['voter_id'];
            $_SESSION['name'] = $voter['name'];
            $_SESSION['age'] = $voter['age'];
            $_SESSION['uname'] = $voter['uname'];
            $_SESSION['email'] = $voter['email'];

            header("Location: user_profile.php");
            exit();
        } else {
            $error = '*Incorrect email or password';
        }
    } else {
        $error = '*No user found with that email';
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
    <title>Login Page</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4" style="border:2px solid black;padding:50px; background-color:rgb(112, 128, 144)">
            <div class="page-header">
                <h2 class="specialHead">Log In</h2>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required><br>
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required><br>
                    <h5 style="color: red;"><?php echo $error; ?></h5>
                    <button type="submit" name="submit_login" class="btn btn-block btn-primary">Log In</button>
                    <button type="button" onclick="window.location.href='signup.php'" class="btn btn-block btn-secondary">Sign Up</button>
                </div>
            </form>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
