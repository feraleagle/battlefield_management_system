<!--
Query 1: Retrieves resource type and details assigned to the logged-in user.
SELECT type, details 
FROM resources 
WHERE pssdBy = '{$_SESSION['cnic']}';
.-->

<?php
session_start();

if (!isset($_SESSION['cnic']) || !$_SESSION['rank']) {
  header('Location: /eagle_bms/login.php');
  exit;
}
include "security/connect_db.php";

// Queries For Demo
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="./assets/css/main.css">
  <link rel="stylesheet" href="./assets/css/resources.css">
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
            <div class="chunkTitle">Resources</div>
            <div>
              <table>
                <thead>
                  <tr>
                    <th>Resource Type</th>
                    <th>Resource Details</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $getResourceDetails = "SELECT type,details FROM resources WHERE pssdBy = '" . $_SESSION['cnic'] . "'";
                  $result = mysqli_query($conn, $getResourceDetails);
                  $counter = 0;
                  while ($row = $result->fetch_assoc()) {
                    echo '<tr>' . '<td>';
                    echo $row['type'];
                    echo '</td>' . '<td>';
                    echo $row['details'];
                    echo '</td>' . '</tr>';
                    $counter++;
                  }
                  if ($counter == 0) {
                    echo "<tr><td>Nothing To Show Here</td><td>Nothing To Show Here</td></tr>";
                  }

                  // Append Demo Queries
                  ?>
                </tbody>
              </table>
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