<?php
session_start();
include 'config.php';
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Statistics</title>

<link href="css/bootstrap.min.css" rel="stylesheet">

<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>

<style>
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
        <a href="#" class="navbar-brand headerFont text-lg" style="color:rgb(112, 128, 144)"><strong>E-Poll</strong></a>
      </div>

      <div class="collapse navbar-collapse" id="example-nav-collapse">
        <ul class="nav navbar-nav">
          <li><a href="adminlanding.php"><span class="subFont"><strong>Admin Info</strong></span></a></li>
          <li><a href="candidate.php"><span class="subFont"><strong>Candidates List</strong></span></a></li>
          <!-- <li><a href="statistics.php"><span class="subFont"><strong>Statistics</strong></span></a></li>         -->
        </ul>
        
        <span class="normalFont"><a href="index.html" class="btn btn-danger navbar-right navbar-btn"><strong>Log Out</strong></a></span>
      </div>
    </div> 
  </nav>
 <br><br><br>

<?php
//Pie Chart
$dataPointsPie = array();
$totalVotes = 0;

$sql = "SELECT candidate.cname, candidate.party, COUNT(votes.cid) AS count_vote 
        FROM votes 
        JOIN candidate ON votes.cid = candidate.cid 
        GROUP BY candidate.cid";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) { 
  $totalVotes += $row["count_vote"];
}

$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) { 
  $percent = $row["count_vote"] * 100 / $totalVotes;
  $label = "Candidate: " . $row["cname"] . ", Party: " . $row["party"];
  array_push($dataPointsPie, array("label" => $label, "y" => $percent));
}

//Bar Graph
$dataPointsBar = array(); 
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) { 
  $label = "Party: " . $row["party"];
  array_push($dataPointsBar, array("y" => $row["count_vote"], "label" => $label));
}

//-------------Line Graph-----------------//
$sql = "SELECT * FROM voter WHERE flag = 1";
$result = mysqli_query($conn, $sql);

$cnt = 0;
while ($row = mysqli_fetch_assoc($result)) { 
  $cnt++;
}

// Just to increase scale
$cnt = $cnt * 5;
$cnt = 76 + $cnt;

$dataPointsLine = array(
  array("y" => 65, "label" => "1996"),
  array("y" => 89, "label" => "2001"),
  array("y" => 101, "label" => "2006"),
  array("y" => 84, "label" => "2011"),
  array("y" => 113, "label" => "2016"),
  array("y" => $cnt, "label" => "2021")
);
?>

<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
  // Pie Chart
  var chartPie = new CanvasJS.Chart("chartContainerPie", {
    animationEnabled: true,
    title: {
      text: "Percentage of Votes Obtained"
    },
    data: [{
      type: "pie",
      yValueFormatString: "#,##0.00\"%\"",
      indexLabel: "{label} ({y})",
      dataPoints: <?php echo json_encode($dataPointsPie, JSON_NUMERIC_CHECK); ?>
    }]
  });
  chartPie.render();

  // Bar Graph
  var chartBar = new CanvasJS.Chart("chartContainerBar", {
    animationEnabled: true,
    title: {
      text: "ELECTION GROWTH"
    },
    axisY: {
      includeZero: true
    },
    data: [{
      type: "bar",
      yValueFormatString: "#,## votes",
      indexLabel: "{y}",
      indexLabelPlacement: "inside",
      indexLabelFontWeight: "bolder",
      indexLabelFontColor: "white",
      dataPoints: <?php echo json_encode($dataPointsBar, JSON_NUMERIC_CHECK); ?>
    }]
  });
  chartBar.render();

  // Line Chart

}
</script>
</head>
<body>
<div class="container" style="padding:30px;">
  <div class="row">
    <div class="col-sm-12" style="border:2px solid gray;">
      <center>
        <br>
        <div id="chartContainerPie" style="height: 400px; width: 100%;"></div>
        <br>
      </center>
    </div>
  </div>
  <br><br>
  <div class="row">
    <div class="col-sm-12" style="border:2px solid gray;">
      <center>
        <br>
        <div id="chartContainerBar" style="height: 400px; width: 80%;"></div>
        <br>
      </center>
    </div>
  </div>
  <br><br>
  <div class="row">
  </div>
</div>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
