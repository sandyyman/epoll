<?php
session_start();
require 'PHPMailer/PHPMailerAutoload.php'; // Include PHPMailer library

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Generate a random 6-digit OTP
$otp = rand(100000, 999999);

// Save OTP in session
$_SESSION['otp'] = $otp;

// Prepare email
$mail = new PHPMailer;

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com'; // Set the SMTP server to send through
    $mail->SMTPAuth = true;
    $mail->Username = 'your-email@example.com'; // SMTP username
    $mail->Password = 'your-email-password'; // SMTP password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port = 587; // TCP port to connect to

    // Recipients
    $mail->setFrom('no-reply@example.com', 'Your App Name');
    $mail->addAddress($_SESSION['email']); // Add recipient's email

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Your OTP Code';
    $mail->Body = 'Your OTP code is: <b>' . $otp . '</b>';
    $mail->AltBody = 'Your OTP code is: ' . $otp;

    $mail->send();
    $message = 'OTP has been sent to your email.';
} catch (Exception $e) {
    $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// Check OTP if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_otp'])) {
    $entered_otp = $_POST['otp'];

    // Verify OTP
    if ($entered_otp == $_SESSION['otp']) {
        header("Location: vote.php");
        exit();
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4" style="border:2px solid black;padding:50px; background-color:rgb(112, 128, 144)">
            <div class="page-header">
                <h2 class="specialHead">Verify OTP</h2>
                <h5 style="color: green;"><?php echo $message; ?></h5>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label>OTP:</label>
                    <input type="text" name="otp" class="form-control" required><br>
                    <h5 style="color: red;"><?php if (isset($error)) echo $error; ?></h5>
                    <button type="submit" name="submit_otp" class="btn btn-block btn-primary">Verify</button>
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
