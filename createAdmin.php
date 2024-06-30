<?php  
session_start();
include 'config.php';
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['createAdmin'])) {
    $newAid = $_POST['newAdminId'];
    $newAname = mysqli_real_escape_string($conn, $_POST['newAdminUserName']);
    $newApwd = $_POST['newAdminPassword']; 

    // Prepare SQL statement to insert new admin data
    // Prepared statement 
    $stmt = $conn->prepare("INSERT INTO admin (aid, aname, apwd) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $newAid, $newAname, $newApwd);

    if ($stmt->execute()) {
        // Set a session variable to indicate admin creation success
        $_SESSION['admin_created'] = true;
        // Redirect to the login page after a delay
        header("refresh:3;url=admin.php");
        echo "<p>Admin created successfully. Redirecting to login page...</p>";
        exit;
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
<div class="container" style="padding-top:50px;">
    <h2 style= "text-align:center;">Create New Admin</h2>
    <form action="createAdmin.php" method="POST">
        <div class="form-group" >
            <label>Admin ID</label>
            <input type="text" name="newAdminId" class="form-control" required>
            <label>Username</label>
            <input type="text" name="newAdminUserName" class="form-control" required>
            <label>Password</label>
            <input type="password" name="newAdminPassword" class="form-control" required>
            <button type="submit" name="createAdmin" class="btn btn-primary" style="margin-top: 10px;">Create Admin</button>
        </div>
    </form>
</div>
</body>
</html>
