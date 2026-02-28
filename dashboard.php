<!--
Query 1: Retrieves the name and location of the user's position based on their position ID.
SELECT name, location 
FROM {$_SESSION['position']}s 
WHERE {$_SESSION['shortpos']}ID = '{$_SESSION['posID']}';

Query 2: Retrieves the date of birth of the user.
SELECT dob 
FROM users 
WHERE cnic = '{$_SESSION['cnic']}';

Query 3: Retrieves mission titles and status assigned to the user, excluding completed or failed missions.
SELECT msnTitle, msnStatus 
FROM missions 
WHERE assgnTo = '{$_SESSION['cnic']}' 
AND msnStatus NOT IN ('Completed','Failed');

Query 4: Retrieves the name and rank of the user's direct senior from the hierarchy.
SELECT name, rank 
FROM users 
WHERE cnic = (SELECT superiorID FROM hierarchy WHERE userID = '{$_SESSION['cnic']}');

Query 5: Counts the number of direct juniors under the user in the hierarchy.
SELECT COUNT(*) AS count 
FROM hierarchy 
WHERE superiorID = '{$_SESSION['cnic']}';
-->


<?php
session_start();

if (!isset($_SESSION['cnic']) || !$_SESSION['rank']) {
  header('Location: /eagle_bms/login.php');
  exit;
}
include 'security/encryptDecrypt.php';

// Queries For Demo
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="./assets/css/main.css">
  <link rel="stylesheet" href="./assets/css/dashboard.css">
  <link rel="shortcut icon" href="./assets/imgs/eagle.png" type="image/x-icon">
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
          <div class="chunk">
            <div class="chunkTitle">User Details</div>
            <div>
              <?php
              include "security/connect_db.php";

              $fmtdPos = strtolower($_SESSION['position']); // Converts Positions To LowerCase [Corp -> corp]
              
              // Query For Selecting Name Of Position
              $queryPosName = "SELECT name,location FROM {$fmtdPos}s WHERE {$_SESSION['shortpos']}ID = '{$_SESSION['posID']}'";
              $result = mysqli_query($conn, $queryPosName)->fetch_assoc();

              // Query For Selecting Date Of Birth Of User & Calculating Age
              $queryDob = "SELECT dob FROM users WHERE cnic = '{$_SESSION['cnic']}'";
              $resultDOB = (mysqli_query($conn, $queryDob))->fetch_assoc();
              $dob = new DateTime($resultDOB['dob']);
              $today = new DateTime();
              $age = $today->diff($dob)->y;


              // Append Demo Queries
              ?>

              <table id="userDetailsTable">
                <tr>
                  <th>Name</th>
                  <td><?php echo htmlspecialchars($_SESSION['name']); ?></td>
                </tr>
                <tr>
                  <th>CNIC</th>
                  <td><?php echo htmlspecialchars($_SESSION['cnic']); ?></td>
                </tr>
                <tr>
                  <th>Age</th>
                  <td><?php echo $age . " Years"; ?></td>
                </tr>
                <tr>
                  <th>Rank</th>
                  <td><?php echo htmlspecialchars($_SESSION['rank']); ?></td>
                </tr>
                <tr>
                  <th><?php echo "{$_SESSION['position']}"; ?></th>
                  <td><?php echo "{$result['name']}" . " - {$result['location']}"; ?></td>
                </tr>
              </table>
            </div>
          </div>
          <div class="chunk">
            <div class="chunkTitle">Missions/Objectives</div>

            <?php
            // Query To Select Mission Title
            $objQuery = "SELECT msnTitle,msnStatus FROM missions WHERE assgnTo = '{$_SESSION['cnic']}' AND msnStatus NOT IN ('Completed','Failed')";
            $resultObj = mysqli_query($conn, $objQuery);

            $objCounter = 1;
            echo "<table id='missionTable'>";
            if ($resultObj->num_rows > 0) {
              echo "<tr>" . "<th>SNo" . "</th>" . "<th>Title" . "</th>" . "</tr>";
              while ($row = $resultObj->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$objCounter}";
                echo "</td>";
                echo "<td>" . decryptMessage($row['msnTitle']) . " ---> {$row['msnStatus']}";
                echo "</td>";
                echo "</tr>";
                $objCounter++;
              }
              echo "</table>";
            } else {
              echo "<tr><td><b>Nothing To Show Here</b></td></tr>";
              echo "</table>";
            }

            // Append Demo Queries
            ?>

          </div>
          <div class="chunk">
            <div class="chunkTitle">Hierarchy</div>

            <?php
            // Query To Select Superior ID
            $hrchyQuery = "SELECT name,rank FROM users WHERE cnic = (SELECT superiorID FROM hierarchy WHERE userID = '{$_SESSION['cnic']}')";
            $resultHRCHY = (mysqli_query($conn, $hrchyQuery))->fetch_assoc();
            // Query To Select Superior ID
            $countQuery = "SELECT COUNT(*) AS count FROM hierarchy WHERE superiorID = '{$_SESSION['cnic']}'";
            $resultCOUNT = (mysqli_query($conn, $countQuery))->fetch_assoc();

            echo "<table id='hierarchyTable'>";
            echo "<tr>";
            echo "<th>Direct Senior</th>";
            echo "<td>" . "{$resultHRCHY['rank']} {$resultHRCHY['name']}" . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Direct Juniors</th>";
            echo "<td>" . "{$resultCOUNT['count']}" . "</td>";
            echo "</tr>";
            echo "</table>";

            // Append Demo Queries
            ?>

          </div>
        </div>

      </div>
    </div>
    <!-- Main Content Ends -->
  </div>
    <div class="chunk">
      <?php
      }
      ?>
  </div>
  <script src="./assets/scripts/refresh.js"></script>
</body>

</html>