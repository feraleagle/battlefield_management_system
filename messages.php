<!--
Queries:
1. Selects users who are working under the current user
   Query: SELECT * FROM hierarchy WHERE superiorID = '{$_SESSION['cnic']}'

2. Gets the superior ID of the current user
   Query: SELECT superiorID FROM hierarchy WHERE userID = '{$_SESSION['cnic']}'

3. Retrieves name, rank, and CNIC of the superior
   Query: SELECT name, rank, cnic FROM users WHERE cnic = '{superiorID}'

4. Fetches user details for each user under the current user
   Query: SELECT name, rank FROM users WHERE cnic = '{userID}'
.-->

<?php
session_start();
if (!isset($_SESSION['cnic']) || !$_SESSION['rank']) {
  header('Location: /eagle_bms/login.php');
  exit;
}
include "security/connect_db.php";
$query = "SELECT * FROM hierarchy WHERE superiorID = '{$_SESSION['cnic']}'";
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

          <div class="chunk">
            <div class="chunkTitle">Send Message</div>
            <form action="./scripts/sendMessage.php" method="post">
              <textarea name="theMessage" id="" required placeholder="Message Here..."></textarea>
              <h4 style="margin-left: 20px;margin-bottom: 10px;">Send Message To</h4>

              <select name="userCNIC" id="">
                <?php
                $tempQuery = mysqli_query($conn, "SELECT superiorID FROM hierarchy WHERE userID = '{$_SESSION['cnic']}'")->fetch_assoc();
                $seniorName = mysqli_query($conn, "SELECT name, rank,cnic FROM users WHERE cnic = '" . $tempQuery['superiorID'] . "'")->fetch_assoc();
                echo '<option value="' . $seniorName['cnic'] . '">' . $seniorName['rank'] . " " . $seniorName['name'];

                // Append Demo Queries
                $tmpQQQ = "SELECT superiorID FROM hierarchy WHERE userID = '{$_SESSION['cnic']}'";
                $tmpQQQe = "SELECT name, rank,cnic FROM users WHERE cnic = '" . $tempQuery['superiorID'] . "'";

                while ($row = $result->fetch_assoc()) {
                  $getNameQuery = "SELECT name, rank FROM users WHERE cnic = '" . $row['userID'] . "'";
                  $getName = mysqli_query($conn, $getNameQuery)->fetch_assoc();
                  echo '<option value="' . $row['userID'] . '">' . $getName['rank'] . " " . $getName['name'];

                  // Append Demo Queries
                }
                ?>
              </select>

              <button type="submit">Send Message</button>
            </form>
          </div>
        </div>

        <div class="chunk">
          <div class="chunkTitle">View Messages</div>
          <form action="./viewChat.php" method="post" style="width:650px">

            <h4 style="margin: 20px;">Please Select To View Chat</h4>
            <select name="userCNIC" id="">
              <?php
              echo '<option value="' . $seniorName['cnic'] . '">' . $seniorName['rank'] . " " . $seniorName['name'];
              $result = mysqli_query($conn, $query);
              while ($row = $result->fetch_assoc()) {
                $getName = mysqli_query($conn, "SELECT name, rank FROM users WHERE cnic = '" . $row['userID'] . "'")->fetch_assoc();
                echo '<option value="' . $row['userID'] . '">' . $getName['rank'] . " " . $getName['name'];
              }
              ?>
            </select>
            <button type="submit">View Chat</button>
          </form>
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