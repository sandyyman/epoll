<?php
session_start();
include 'config.php'; // Include your database configurations
require './PHPMailer/PHPMailer-5.2-stable/PHPMailerAutoload.php';

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_SESSION['email'] ?? '';
echo "Sending OTP to: $email"; // Print the email address to which the OTP is being sent

$otp_error = '';

// Send OTP logic
if (isset($_POST['send_otp'])) {
    $otp = rand(100000, 999999);

    // Insert the OTP into the database
    $stmt = $conn->prepare("INSERT INTO otp_expiry (email, otp) VALUES (?, ?) ON DUPLICATE KEY UPDATE otp = ?");
    $stmt->bind_param("sss", $email, $otp, $otp);
    $stmt->execute();

    // Setup PHPMailer to send the OTP email
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 3; // Enable verbose debug output (disabled for browser)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'epollad01@gmail.com';
    $mail->Password = 'oedomsvrdhaieeik';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('epollad01@gmail.com', 'E-Poll Administration');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = 'This is your OTP code: <b>' . $otp . '</b>';
    $mail->AltBody = 'This is your OTP code: ' . $otp;

    if (!$mail->send()) {
        // Log the error to a file
        error_log('Mailer Error: ' . $mail->ErrorInfo, 3, 'errors.log');
        echo 'Message could not be sent. Please try again later.';
    } else {
        echo 'An OTP has been sent to your email.';
    }
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $user_otp = $_POST['otp'];

    // Prepare a select statement to check the OTP
    //Email from session and opt is stored in $user_otp
    $stmt = $conn->prepare("SELECT * FROM otp_expiry WHERE email = ? AND otp = ?");
    $stmt->bind_param("ss", $email, $user_otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // OTP is correct, delete the OTP entry and redirect to vote.php
        // Deleting otp so that voter can verify and go to polling booth but reject when voted again
        $delete_stmt = $conn->prepare("DELETE FROM otp_expiry WHERE email = ?");
        $delete_stmt->bind_param("s", $email);
        $delete_stmt->execute();

        header("Location: vote.php");
        exit();
    } else {
        $otp_error = 'Invalid OTP.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>OTP Verification</h2>
            <p>Please check your email for the OTP.</p>
            <p>OTP sent to: <?php echo htmlspecialchars($email); ?></p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" name="otp" class="form-control" required>
                </div>
                <button type="submit" name="verify_otp" class="btn btn-primary">Verify OTP</button>
                <div class="form-group">
                    <span class="text-danger"><?php echo $otp_error; ?></span>
                </div>
            </form>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button type="submit" name="send_otp" class="btn btn-secondary">Send OTP</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
