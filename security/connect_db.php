<?php
$servername = "127.0.0.1";
$username = "eagle";
$password = "";
$database = "eagle_bms";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
if (mysqli_connect_errno()) {
  echo "Connect Error!";
}