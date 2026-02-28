<!--
Query 1: Retrieves messages exchanged between the logged-in user and the specified user. It orders messages on basis of TimeStamp;
  SELECT msgTime, message FROM messages WHERE 
  (intendedFor = '{$_SESSION['cnic']}' AND retrievedFrom = '{$_POST['userCNIC']}') 
  OR (retrievedFrom = '{$_SESSION['cnic']}' AND intendedFor = '{$_POST['userCNIC']}')
  ORDER BY msgTime;
.-->

<?php
session_start();

if (!isset($_SESSION['cnic']) || !$_SESSION['rank']) {
  header('Location: /eagle_bms/login.php');
  exit;
}
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "security/connect_db.php";
$query = "SELECT msgTime,message FROM messages WHERE (intendedFor = '{$_SESSION['cnic']}' AND retrievedFrom = '{$_POST['userCNIC']}') OR (retrievedFrom = '{$_SESSION['cnic']}' AND intendedFor = '{$_POST['userCNIC']}') ORDER BY msgTime";
$result = mysqli_query($conn, $query);

// Queries For Demo

// Append Demo Queries
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Missions</title>
<link rel="stylesheet" href="./assets/css/main.css">
<link rel="stylesheet" href="./assets/css/messages.css">
<link rel="shortcut icon" href="./assets/imgs/eagle.png" type="image/x-icon">
<style>
  .hrefMSN {
    color: white;
  }

  .hrefMSN:hover {
    color: rgb(0, 28, 44);
  }

  #msnAssignedTbl tr th:nth-child(3) {
    width: 50px;
    padding: 7px 10px;
  }

  /* width */
  ::-webkit-scrollbar {
    width: 12px;
  }

  /* Track */
  ::-webkit-scrollbar-track {
    background: white;
  }

  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: rgb(0, 57, 90);
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: rgb(0, 57, 90);
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
    <div id="content" style="height: 100vh;overflow-y: scroll;scroll-behavior: smooth">
      <div id="contentWrapper">
        <!-- Header Or Title Line Of Main Content-->
        <div class="contentContainer">

          <div class="chunk" style="min-width:800px;">
            <!-- Display Name In Title -->
            <div class="chunkTitle">View Chat With
              <?php
              $chunkTitleQuery = "SELECT name FROM users WHERE cnic = '{$_POST['userCNIC']}'";
              echo (mysqli_query($conn, $chunkTitleQuery)->fetch_assoc())['name'];
              ?>
            </div>
            <table>
              <?php
              while ($row = $result->fetch_assoc()) {
                echo '<tr><td><b>' . $row['msgTime'] . "</b> " . $row['message'] . "</td></tr>";
              }

              // Append Demo Queries
              ?>
            </table>
          </div>
        </div>
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