<!--
Query 1: Counts the number of missions assigned by or to the user with 'Failed' status.
SELECT COUNT(*) AS count FROM missions 
WHERE (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}') AND msnStatus = 'Failed';

Query 2: Counts the number of missions assigned by or to the user with 'Completed' status.
SELECT COUNT(*) AS count FROM missions 
WHERE (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}') AND msnStatus = 'Completed';

Query 3: Counts the number of ongoing missions assigned to the user.
SELECT COUNT(*) AS count FROM missions 
WHERE assgnTo = '{$_SESSION['cnic']}' AND msnStatus = 'In Progress';

Query 4: Counts the number of pending missions assigned to the user that are 'On Hold' or 'Not Started'.
SELECT COUNT(*) AS count FROM missions 
WHERE assgnTo = '{$_SESSION['cnic']}' AND (msnStatus = 'On Hold' OR msnStatus = 'Not Started');

Query 5: Fetches mission details for missions assigned by the user that are not 'Completed' or 'Failed'.
SELECT msnID, msnStatus, msnTitle, assgnTo, assgnBy FROM missions 
WHERE assgnBy = '{$_SESSION['cnic']}' AND (msnStatus != 'Completed' AND msnStatus != 'Failed');

Query 6: Fetches mission details for missions assigned to the user that are not 'Completed' or 'Failed'.
SELECT msnID, msnStatus, msnTitle, assgnTo, assgnBy FROM missions 
WHERE assgnTo = '{$_SESSION['cnic']}' AND msnStatus NOT IN ('Completed', 'Failed');

Query 7: Retrieves user IDs of subordinates under the logged-in user for mission assignment.
SELECT * FROM hierarchy 
WHERE superiorID = '{$_SESSION['cnic']}';
.-->


<?php
session_start();

if (!isset($_SESSION['cnic']) || !$_SESSION['rank']) {
  header('Location: /eagle_bms/login.php');
  exit;
}
include "security/connect_db.php";
include "security/encryptDecrypt.php"
;
$queryMsnFailed = "SELECT COUNT(*) AS count FROM missions WHERE (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}' ) AND msnStatus = 'Failed'";
$queryMsnCompleted = "SELECT COUNT(*) AS count FROM missions WHERE (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}' ) AND msnStatus = 'Completed'";
$queryMsnOnGoing = "SELECT COUNT(*) AS count FROM missions WHERE (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}' ) AND msnStatus = 'In Progress'";
$queryMsnPending = "SELECT COUNT(*) AS count FROM missions WHERE (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}' ) AND (msnStatus = 'On Hold' OR msnStatus = 'Not Started')";

$resultMsnFailed = (mysqli_query($conn, $queryMsnFailed))->fetch_assoc();
$resultMsnCompleted = (mysqli_query($conn, $queryMsnCompleted))->fetch_assoc();
$resultMsnOnGoing = (mysqli_query($conn, $queryMsnOnGoing))->fetch_assoc();
$resultMsnPending = (mysqli_query($conn, $queryMsnPending))->fetch_assoc();


$queryMsnTitleUpdate = "SELECT msnID,msnStatus,msnTitle,assgnTo,assgnBy FROM missions WHERE assgnBy = '{$_SESSION['cnic']}' AND (msnStatus != 'Completed' AND msnStatus !='Failed')";
$resultMsnTitleUpdate = mysqli_query($conn, $queryMsnTitleUpdate);

$queryMsnTitleFetch = "SELECT msnID,msnStatus,msnTitle,assgnTo,assgnBy FROM missions WHERE assgnTo = '{$_SESSION['cnic']}' AND msnStatus NOT IN ('Completed', 'Failed')";
$resultMsnTitleFetch = mysqli_query($conn, $queryMsnTitleFetch);

$queryAsgnMission = "SELECT * FROM hierarchy WHERE superiorID = '{$_SESSION['cnic']}'";
$resultAsgnMission = mysqli_query($conn, $queryAsgnMission);
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Missions</title>
<link rel="stylesheet" href="./assets/css/main.css">
<link rel="stylesheet" href="./assets/css/missions.css">
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
            <div class="chunkTitle">Missions Overview</div>
            <div>
              <table id="msnOverviewTbl">
                <tr>
                  <th><a class="hrefMSN" href="missions_overview.php?msnTp=Completed">Missions Completed</a></th>
                  <td><?php echo $resultMsnCompleted['count'] ?></td>
                </tr>
                <tr>
                  <th><a class="hrefMSN" href="missions_overview.php?msnTp=Failed">Missions Failed</a></th>
                  <td><?php echo $resultMsnFailed['count'] ?></td>
                </tr>
                <tr>
                  <th><a class="hrefMSN" href="missions_overview.php?msnTp=In%20Progress">Ongoing Missions</a></th>
                  <td><?php echo $resultMsnOnGoing['count'] ?></td>
                </tr>
                <tr>
                  <th><a class="hrefMSN" href="missions_overview.php?msnTp=On%20Hold">Pending Missions</a></th>
                  <td><?php echo $resultMsnPending['count'] ?></td>
                </tr>
              </table>
            </div>
          </div>

          <div class="chunk">
            <div class="chunkTitle">Update Mission Status</div>
            <div>
              <form action="./scripts/updateMissionStatus.php" method="post">
                <table id="msnStatus">
                  <tr>
                    <th><b>Title</b></th>
                    <th><b>Status</b></th>
                  </tr>
                  <tr>
                    <td class="msnStatus-i">
                      <select name="msnID" style="min-width: 265px;background-color:rgb(0, 57, 90);color:white">
                        <?php
                        while ($row = $resultMsnTitleUpdate->fetch_assoc()) {
                          $nameOfUser = mysqli_query($conn, "SELECT name FROM users WHERE cnic = '{$row['assgnTo']}'")->fetch_assoc();
                          echo '<option value="' . $row['msnID'] . '">' . decryptMessage($row['msnTitle']) . " (" . $nameOfUser['name'] . ' : ' . $row['msnStatus'] . ')</option>';
                        }
                        ?>
                      </select>
                    </td>
                    <td class="msnStatus-ii">
                      <select name="msnStatus" id="selectMSN">
                        <option value='Completed'>Completed</option>
                        <option value='Failed'>Failed</option>
                        <option value='Not Started'>Not Started</option>
                        <option value='In Progress'>In Progress</option>
                        <option value='On Hold'>On Hold</option>
                      </select>
                    </td>
                  </tr>
                </table>
                <button id="updateSubmit" type="submit">Update Mission Status</button>
              </form>
            </div>
          </div>

          <div class="chunk">
            <div class="chunkTitle">Missions Assigned To You</div>
            <div>
              <table id="msnAssignedTbl">
                <tr>
                  <th>SNo</th>
                  <th>Title</th>
                  <th>Status</th>
                </tr>
                <?php
                $fetchCounter = 1;
                while ($row = $resultMsnTitleFetch->fetch_assoc()) {
                  echo '<tr><td>' . $fetchCounter . '</td>';
                  echo '<td><a href="./displayMissionDetails.php?msnID=' . $row['msnID'] . '">' . decryptMessage($row['msnTitle']) . '</a></td>';
                  echo '<td>' . $row['msnStatus'] . '</tr></td>';
                  $fetchCounter++;
                }
                ?>
              </table>
            </div>
          </div>

          <div class="chunk">
            <div class="chunkTitle">Assign Mission</div>
            <div>
              <form onsubmit="validateForm(event)" id="asgnMission" action="./scripts/assignMission.php" method="post">
                <input name="missionTitle" type="text" placeholder="Mission Title" required>
                <textarea style="overflow-y:scroll;" maxlength="5500" required name="missionDesc"
                  placeholder="Mission Description..."></textarea>
                <div id="selectUsers">
                  <table>
                    <?php
                    while ($row = $resultAsgnMission->fetch_assoc()) {
                      $selectUsernameQuery = "SELECT name,rank,position FROM users WHERE cnic = '{$row['userID']}'";
                      $resultSelectUsername = mysqli_query($conn, $selectUsernameQuery)->fetch_assoc();
                      if ($resultSelectUsername['rank'] != 'Major' && $resultSelectUsername['rank'] != 'Captain' && $resultSelectUsername['rank'] != 'Non Officer') {

                        $PositionLowerCase = strtolower($resultSelectUsername['position']);
                        $fetchPositionName = mysqli_query($conn, "SELECT name FROM {$PositionLowerCase}s WHERE headedBy = '{$row['userID']}'")->fetch_assoc();
                        echo '<tr><td class="tblUsername">' . $resultSelectUsername['rank'] . " " . $resultSelectUsername['name'] . " [{$fetchPositionName['name']}]" . '</td>';

                      } else {
                        echo '<tr><td class="tblUsername">' . $resultSelectUsername['rank'] . " " . $resultSelectUsername['name'] . '</td>';
                      }
                      echo '<td><input class="tblChkbox" type="checkbox" name="' . $row['userID'] . '"></td></tr>';
                    }
                    ?>
                  </table>
                </div>
                <div style="text-align: center;">
                  <select name="msnStatus" id="selectMSN">
                    <option value='Not Started'>Not Started</option>
                    <option value='In Progress'>In Progress</option>
                    <option value='On Hold'>On Hold</option>
                  </select>
                  <input type="hidden" id="checkedUsers" name="checkedUsers">
                  <button id="asgnSubmit" type="submit">Assign Mission</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Main Content Ends -->
  </div>

  <div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
      <span class="custom-modal-close" onclick="closeModal()">&times;</span>
      <p id="modalMessage"></p>
    </div>
  </div>
  <script>
    function collectCheckedCheckboxes() {
      var checkboxes = document.querySelectorAll('input[type="checkbox"].tblChkbox');
      var checkedValues = [];

      checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
          checkedValues.push(checkbox.name);
        }
      });

      // Set the values to a hidden input field
      var hiddenInput = document.getElementById('checkedUsers');
      hiddenInput.value = checkedValues.join(',');
    }

    function validateForm(event) {
      collectCheckedCheckboxes(); // Collect checked checkboxes before form submission

      var checkboxes = document.querySelectorAll('input[type="checkbox"].tblChkbox');
      var isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

      if (!isChecked) {
        // Prevent form submission
        event.preventDefault();
        alert("Please select at least one user.");
      }
    }
    function showModal(message) {
      var modal = document.getElementById("customModal");
      var modalMessage = document.getElementById("modalMessage");
      modalMessage.textContent = message;
      modal.style.display = "block";

      // Hide the modal after 5 seconds
      setTimeout(function () {
        modal.style.display = "none";
      }, 5000);
    }
    function closeModal() {
      var modal = document.getElementById("customModal");
      modal.style.display = "none";
    }
  </script>
    <div class="chunk">
      <?php
      echo "Get User ID Chunk<br>";
      echo "<br>Query 1: Counts the number of missions assigned by or to the user with 'Failed' status.<br>";
      echo "SELECT COUNT(*) AS count FROM missions WHERE (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}') AND msnStatus = 'Failed'<br>";

      echo "<br>Query 2: Counts the number of missions assigned by or to the user with 'Completed' status.<br>";
      echo "SELECT COUNT(*) AS count FROM missions WHERE (assgnBy = '{$_SESSION['cnic']}' OR assgnTo = '{$_SESSION['cnic']}') AND msnStatus = 'Completed'<br>";

      echo "<br>Query 3: Counts the number of ongoing missions assigned to the user.<br>";
      echo "SELECT COUNT(*) AS count FROM missions WHERE assgnTo = '{$_SESSION['cnic']}' AND msnStatus = 'In Progress'<br>";

      echo "<br>Query 4: Counts the number of pending missions assigned to the user that are 'On Hold' or 'Not Started'.<br>";
      echo "SELECT COUNT(*) AS count FROM missions WHERE assgnTo = '{$_SESSION['cnic']}' AND (msnStatus = 'On Hold' OR msnStatus = 'Not Started')<br>";

      echo "<br>Query 5: Fetches mission details for missions assigned by the user that are not 'Completed' or 'Failed'.<br>";
      echo "SELECT msnID, msnStatus, msnTitle, assgnTo, assgnBy FROM missions WHERE assgnBy = '{$_SESSION['cnic']}' AND (msnStatus != 'Completed' AND msnStatus != 'Failed')<br>";

      echo "<br>Query 6: Fetches mission details for missions assigned to the user that are not 'Completed' or 'Failed'.<br>";
      echo "SELECT msnID, msnStatus, msnTitle, assgnTo, assgnBy FROM missions WHERE assgnTo = '{$_SESSION['cnic']}' AND msnStatus NOT IN ('Completed', 'Failed')<br>";

      echo "<br>Query 7: Retrieves user IDs of subordinates under the logged-in user for mission assignment.<br>";
      echo "SELECT * FROM hierarchy WHERE superiorID = '{$_SESSION['cnic']}'<br>";
      ?>
  </div>
  <script src="./assets/scripts/refresh.js"></script>
</body>

</html>