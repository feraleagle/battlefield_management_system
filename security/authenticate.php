<?php
include "connect_db.php"; // Ensure this file establishes a connection and assigns it to $conn

// Check if the connection is successful and if the request method is POST
if ($conn && $_SERVER['REQUEST_METHOD'] == "POST") {
    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("SELECT * FROM users WHERE cnic = ?");

    // Check if the statement preparation was successful
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Construct the CNIC by combining the POST inputs
    $cnic = $_POST["cnic0"] . '-' . $_POST["cnic1"] . '-' . $_POST["cnic2"];
    // Bind parameters to the prepared statement
    $stmt->bind_param("s", $cnic);
    // Execute the statement
    $stmt->execute();
    // Get the result
    $result = $stmt->get_result();

    // Check if there is a matching row
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($_POST["pswd"], $user['pswd'])) {
            if ($user['rank'] == 'General') {
                session_regenerate_id(true);
                session_start();
                header("Location: /eagle_bms/highestCommand/dashboard.php");
            } else {
                // Start the session with secure settings
                session_regenerate_id(true);
                session_start();
                $queryUserDetails = "SELECT * FROM userDetails WHERE cnic = '{$cnic}'";
                $result = (mysqli_query($conn, $queryUserDetails))->fetch_assoc();

                $posID;
                $posNo;
                if ($result['battalionID'] != null) {
                    $posID = $result['battalionID'];
                    $posNo = 0;
                } else if ($result['brigadeID'] != null) {
                    $posID = $result['brigadeID'];
                    $posNo = 1;
                } else if ($result['divisionID'] != null) {
                    $posID = $result['divisionID'];
                    $posNo = 2;
                } else if ($result['corpID'] != null) {
                    $posID = $result['corpID'];
                    $posNo = 3;
                } else {
                    $posID = null;
                    $posNo = 4;
                }
                // Use match expression to map position to short form
                $shortpos = match ($result['position']) {
                    "Battalion" => 'btln',
                    "Brigade" => 'brg',
                    "Division" => 'div',
                    "Corp" => 'corp',
                    default => 'unknown', // Handle unexpected or missing values
                };

                $structure_arr = ["Battalion", "Brigade", "Divison", "Corp"];
                $shortpos_arr = ['btln', 'brg', 'div', 'corp'];

                $_SESSION['cnic'] = $result['cnic'];
                $_SESSION['rank'] = $result['rank'];
                $_SESSION['name'] = $result['name'];
                $_SESSION['position'] = $result['position'];
                $_SESSION['posID'] = $posID;
                $_SESSION['supID'] = $result['superiorID'];
                $_SESSION['shortpos'] = $shortpos;
                $_SESSION['posNO'] = $posNo;
                $_SESSION['structure_arr'] = $structure_arr;
                $_SESSION['shortpos_arr'] = $shortpos_arr;

                header("Location: /eagle_bms/dashboard.php");
            }
        } else {
            // echo "Unsuccessful 000";
            header("Location: /eagle_bms/login.php?WRP=1");
        }
    } else {
        // echo "Unsuccessful 001";
        header("Location: /eagle_bms/login.php?WRP=1");
    }

    // Close the statement
    $stmt->close();
} else {
    // echo "Unsuccessful 002";
    header("Location: /eagle_bms/login.php?WRP=1");
}

// Close the connection
$conn->close();
