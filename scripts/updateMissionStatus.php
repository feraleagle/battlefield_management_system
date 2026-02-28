<!--
Query: Updates the status of a specific mission in the missions table based on the mission ID and the CNIC of the user who assigned it.
UPDATE missions 
SET msnStatus = '{$_POST['msnStatus']}' 
WHERE (msnID = {$_POST['msnID']} AND assgnBy = '{$_SESSION['cnic']}');
.-->


<?php
session_start();

include "../security/connect_db.php";

if (!isset($_SESSION['cnic']) || !$_SESSION['rank'] || $_SERVER['REQUEST_METHOD'] != 'POST') {
  header('Location: /eagle_bms/login.html');
  exit;
}

$assignMissionQuery = "UPDATE missions SET msnStatus = '{$_POST['msnStatus']}' WHERE (msnID = {$_POST['msnID']} AND assgnBy = '{$_SESSION['cnic']}')";
mysqli_query($conn, $assignMissionQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="5;url=http://127.0.0.1/eagle_bms/missions.php">
  <title>Redirecting...</title>
  <style>
    html {
      background-color: rgb(12, 67, 99);
      color: white;
    }
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin-top: 50px;
    }

    .countdown {
      font-size: 24px;
      font-weight: bold;
    }

    .message {
      font-size: 18px;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <h1>Mission Status Updated Successfully</h1>
  <p class="message">You will be redirected in <span id="countdown" class="countdown">3</span> seconds...</p>
  <script>
    function startCountdown(seconds, url) {
      var countdownElement = document.getElementById('countdown');
      var interval = setInterval(function () {
        countdownElement.textContent = seconds;
        seconds--;

        if (seconds < 0) {
          clearInterval(interval);
          window.location.href = url;
        }
      }, 900);
    }

    window.onload = function () {
      startCountdown(3, 'http://127.0.0.1/eagle_bms/missions.php'); // 5 seconds countdown
    };
  </script>
</body>

</html>