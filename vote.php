<?php
session_start();
include 'config.php';

// Connect to the database
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submitVote'])) {
    $voter_id = $_SESSION["voter_id"];
    $radioVal_cid = $_POST['VoteRadio'];

    // Check if the voter has already voted
    $checkVote = "SELECT * FROM votes WHERE voter_id='$voter_id'";
    $result = mysqli_query($conn, $checkVote);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('You have already voted.'); window.location.href = 'logout.php';</script>";
    } else {
        // Insert the vote into the votes table
        $sqlVote = "INSERT INTO votes (voter_id, cid) VALUES ('$voter_id', '$radioVal_cid')";

        // Update the voter table to mark that the voter has voted
        $sqlUpdateVoter = "UPDATE voter SET flag=1 WHERE voter_id='$voter_id'";

        // Update the candidate table to increment the vote count
        $sqlUpdateCandidate = "UPDATE candidate SET count_vote = count_vote + 1 WHERE cid='$radioVal_cid'";

        if (mysqli_query($conn, $sqlVote) && mysqli_query($conn, $sqlUpdateVoter) && mysqli_query($conn, $sqlUpdateCandidate)) {
            echo "<script>alert('Thanks for voting!'); window.location.href = 'logout.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch candidates
$sql = "SELECT * FROM candidate";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vote Page</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        table, th, td {
            text-align: center;
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
        .icon-input-btn {
            display: inline-block;
            position: relative;
        }
        .icon-input-btn input[type="submit"] {
            padding-left: 2em;
        }
        .icon-input-btn .glyphicon {
            display: inline-block;
            position: absolute;
            left: 0.3em;
            top: 24%;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
        <div class="container">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-header">
                <a href="#" class="navbar-brand headerFont text-lg" sstyle="color:rgb(112, 128, 144)"><strong>E-Poll</strong></a>
            </div>
        </div>
    </nav>  

    <center>
        <div class="container" style="padding:100px 10px 20px 10px;">
            <div class="row">
                <div class="col-sm-12" style="border:2px solid gray;">
                    <center>
                        <div class="page-header">
                            <h2 class="specialHead">CAST YOUR VOTE WISELY, <?php echo $_SESSION["name"]; ?>!</h2><br>
                            <form method="POST" action="vote.php">
                                <table style="width:75%; border-collapse: collapse;" border="1px">
                                    <tr>
                                        <th>Political Party</th>
                                        <th>Candidate Name</th>
                                        <th>PHOTO</th>
                                    </tr>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="VoteRadio" id="<?php echo $row["party"]; ?>" value="<?php echo $row["cid"]; ?>">
                                                <label class="form-check-label" for="<?php echo $row["party"]; ?>"><?php echo $row["party"]; ?></label>
                                            </div>
                                        </td>
                                        <td><?php echo $row["cname"]; ?></td>
                                        <td><img src="<?php echo 'images/' . $row["cphoto"]; ?>" alt="img not found!" width="60px" height="60px"></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                                <br>
                                <div class="form-group-row">
                                    <input type="submit" class="btn btn-primary" name="submitVote" value="SUBMIT VOTE">
                                </div>
                            </form>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </center>
</div>
</body>
</html>

<?php
mysqli_close($conn);
?>
