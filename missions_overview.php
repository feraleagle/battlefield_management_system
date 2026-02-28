<!--
Query 1: Retrieves mission titles, IDs, and assigned users for missions where the status matches the specified type and either assigned by or assigned to the logged-in user.
SELECT msnTitle, msnID, assgnTo 
FROM missions 
WHERE msnStatus = '{$_GET['msnTp']}' AND (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}');
-->

<!--
Query 2: Fetches the rank and name of the user assigned to each mission based on their cnic.
SELECT rank, name 
FROM users 
WHERE cnic = '{$row['assgnTo']}';
-->


<?php
session_start();

include "security/connect_db.php";
include "./security/encryptDecrypt.php";

if (!isset($_SESSION['cnic']) || !$_SESSION['rank'] || !isset($_GET['msnTp'])) {
  header('Location: /eagle_bms/login.php');
  exit;
}

if ($_GET['msnTp'] == 'On Hold') {
  $query = "SELECT msnTitle,msnID,assgnTo FROM missions WHERE  msnStatus IN ('On Hold','In Progress') AND assgnBy = '{$_SESSION['cnic']}' OR msnStatus IN ('On Hold','In Progress') AND assgnTo = '{$_SESSION['cnic']}'";
} else {
  $query = "SELECT msnTitle,msnID,assgnTo FROM missions WHERE  msnStatus = '" . $_GET['msnTp'] . "' AND assgnBy = '{$_SESSION['cnic']}' OR msnStatus = '". $_GET['msnTp'] . "' AND assgnTo = '{$_SESSION['cnic']}'";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Overview Missions</title>
  <link rel="stylesheet" href="./assets/css/main.css">
  <link rel="stylesheet" href="./assets/css/dashboard.css">
  <link rel="shortcut icon" href="./assets/imgs/eagle.png" type="image/x-icon">
  <style>
    table {
      margin: 20px auto;
    }

    table tr td a {
      color: white;
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
      width: 60vw;
    }

    .SNO {
      width: 30px;
      text-align: center;
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
              echo $msnType . " Missions";
              ?>
            </div>
            <table style="color:white;">
              <tr>
                <th class="SNO">SNo</th>
                <th>Title</th>
              </tr>
              <?php
              $counter = 1;
              while ($row = $result->fetch_assoc()) {
                echo "<tr><td class = 'SNO'>" . $counter . "</td>";
                $query2 = mysqli_query($conn, "SELECT rank,name FROM users WHERE cnic = '" . $row['assgnTo'] . "' OR cnic = '{$row['assgnBy']}'")->fetch_assoc();
                echo "<td><a href='displayMissionDetails.php?msnID=" . $row['msnID'] . "'>" . decryptMessage($row['msnTitle']) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Assigned To ---> " . $query2['rank'] . " {$query2['name']}" . "]</a></td></tr>";
                $counter++;
              }
              ?>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- Main Content Ends -->
  </div>
    <div class="chunk">
      <?php
      echo "Missions Overview Chunk<br>";

      echo "<br>Query 1: Retrieves mission titles, IDs, and assigned users for missions where the status matches the specified type and either assigned by or assigned to the logged-in user.<br>";
      echo "SELECT msnTitle, msnID, assgnTo FROM missions WHERE msnStatus = '{$_GET['msnTp']}' AND (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}')<br>";

      echo "<br>Query 2: Fetches the rank and name of the user assigned to each mission based on their cnic.<br>";
      echo "SELECT rank, name FROM users WHERE cnic = '{$row['assgnTo']}'<br>";

      ?>
  </div>
  <script src="./assets/scripts/refresh.js"></script>
</body>

</html>