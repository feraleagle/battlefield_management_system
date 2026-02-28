<!--
Query 1: Retrieves mission details based on the mission ID from the URL.
SELECT * 
FROM missions 
WHERE msnID = {$_GET['msnID']};

Query 2: Retrieves the name and rank of the user who assigned the mission.
SELECT name, rank 
FROM users 
WHERE cnic = '{result['assgnBy']}' ;

Query 3: Retrieves the name and rank of the user to whom the mission is assigned.
SELECT name, rank 
FROM users 
WHERE cnic = '{result['assgnTo']}' ;
-->


<?php
session_start();

include "security/connect_db.php";
include "./security/encryptDecrypt.php";

if (!isset($_SESSION['cnic']) || !$_SESSION['rank'] || !isset($_GET['msnID'])) {
  header('Location: /eagle_bms/login.php');
  exit;
}

$msnID = $_GET['msnID'];
$msnID = filter_var($msnID, FILTER_SANITIZE_NUMBER_INT);

$query = "SELECT * FROM missions WHERE msnID = " . $msnID;
$result = mysqli_query($conn, $query)->fetch_assoc();

$assgnBy = mysqli_query($conn, "SELECT name,rank FROM users WHERE cnic = '" . $result['assgnBy'] . "'")->fetch_assoc();
$assgnTo = mysqli_query($conn, "SELECT name,rank FROM users WHERE cnic = '" . $result['assgnTo'] . "'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mission Details</title>
  <link rel="stylesheet" href="./assets/css/main.css">
  <link rel="stylesheet" href="./assets/css/dashboard.css">
  <link rel="shortcut icon" href="./assets/imgs/eagle.png" type="image/x-icon">
  <style>
    table {
      margin: 20px auto;
    }

    table tr th {
      width: 20vw;
      background-color: rgb(0, 57, 90);
      padding: 5px 15px;
      color: white;
      border-radius: 5px;
    }

    table tr td {
      border-radius: 5px;
      background-color: rgb(0, 57, 90);
      padding: 5px 15px;
      width: 40vw;
    }
  </style>
</head>

<body>
  <div id="container">

    <!-- SideBar -->
    <div id="sidebar">
      <a href="dashboard.php">
        <div class="selectableLinks task_buttons hover-box">
          <img src="./assets/svgs/dashboard.svg">
          <span><b>Dashboard</b></span>
        </div>
      </a>
      <a href="missions.php">
        <div class="selectableLinks task_buttons hover-box">
          <img src="./assets/svgs/mission.svg">
          <span><b>Missions</b></span>
        </div>
      </a>
      <a href="resources.php">
        <div class="selectableLinks task_buttons hover-box">
          <img src="./assets/svgs/resources.svg">
          <span><b>Resources</b></span>
        </div>
      </a>
      <a href="intel.php">
        <div class="selectableLinks task_buttons hover-box">
          <img src="./assets/svgs/intel.svg">
          <span><b>Intelligence</b></span>
        </div>
      </a>
      <a href="messages.php">
        <div class="selectableLinks task_buttons hover-box">
          <img src="./assets/svgs/communicate.svg">
          <span><b>Messages</b></span>
        </div>
      </a>
      <a href="subordinates.php">
        <div class="selectableLinks task_buttons hover-box">
          <img src="./assets/svgs/sbordinates.svg">
          <span><b>Subordinates</b></span>
        </div>
      </a>
      <a href="./security/logout.php">
        <div class="selectableLinks task_buttons" style="position: absolute; bottom: 0">
          <img src="./assets/svgs/logout.svg">
          <span><b>Logout</b></span>
        </div>
      </a>
    </div>
    <!-- SideBar Ends -->
    <!-- Main Content -->
    <div id="content">
      <div id="contentWrapper">
        <!-- Header Or Title Line Of Main Content-->
        <div class="contentContainer">
          <div style="width: 70vw;" class="chunk">
            <div class="chunkTitle">
              <?php
              echo decryptMessage($result['msnTitle']);
              ?>
            </div>
            <table style="color:white;">
              <tr>
                <th>Assigned By</th>
                <td> <?php
                echo $assgnBy['rank'] . " " . $assgnBy['name'];
                ?></td>
              </tr>
              <tr>
                <th>Assigned To</th>
                <td> <?php
                echo $assgnTo['rank'] . " " . $assgnTo['name'];
                ?>
                </td>
              </tr>
              <tr>
                <th>Mission Status</th>
                <td> <?php
                echo $result['msnStatus'];
                ?>
                </td>
              </tr>
            </table>
            <div>
              <div class="chunkTitle" style="width: 60vw; margin:auto">
                Mission Description
              </div>
              <div
                style="width: 60vw;padding: 2px 10px; margin:auto;border:10px solid rgb(0, 57, 90);border-radius:0px 0px 8px 8px;margin-bottom:20px;">
                <?php
                echo decryptMessage($result['msnDesc']);
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Main Content Ends -->
  </div>
</body>

</html>