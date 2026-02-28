<!--
Query: Inserts a new message record into the messages table with the current timestamp, sender's CNIC, recipient's CNIC, and the message content.
INSERT INTO messages (msgTime, retrievedFrom, intendedFor, message) 
VALUES (CURRENT_TIMESTAMP(), '{$_SESSION['cnic']}', '{$_POST['userCNIC']}', '{$_POST['theMessage']}');
.-->


<?php
session_start();

include "../security/connect_db.php";
mb_internal_encoding("UTF-8");

if (!isset($_SESSION['cnic']) || !$_SESSION['rank'] || $_SERVER['REQUEST_METHOD'] != 'POST') {
  header('Location: /eagle_bms/login.html');
  exit;
}
$query = "INSERT INTO messages (msgTime, retrievedFrom, intendedFor, message) VALUES (CURRENT_TIMESTAMP(), '{$_SESSION['cnic']}', '{$_POST['userCNIC']}', '{$_POST['theMessage']}')";
mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="5;url=http://127.0.0.1/eagle_bms/missions.php">
  <title>Redirecting...</title>
  <style>
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

    html {
      background-color: rgb(12, 67, 99);
      color: white;
    }
  </style>
</head>

<body>
  <h1>Message Sent Successfully</h1>
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
      startCountdown(3, 'http://127.0.0.1/eagle_bms/messages.php'); // 5 seconds countdown
    };
  </script>
</body>

</html>