<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Eagle's Nest</title>
  <link rel="shortcut icon" href="./assets/imgs/eagle.png" type="image/x-icon">
  <style>
    * {
      box-sizing: border-box;
      color: white;
      margin: 0;
      padding: 0;
    }

    html {
      background-color: rgb(45, 102, 136);
    }

    .cnic {
      text-align: center;
      margin: 5px;
      padding: 5px 2px;
      font-size: 15px;
      color: black;
      border-radius: 5px;
    }

    #cnic0 {
      width: 60px;
      margin-left: 29px;
    }

    #cnic1 {
      width: 90px;
    }

    #cnic2 {
      width: 20px;
    }

    #pass input {
      margin: 5px;
      padding: 5px 10px;
      border-radius: 5px;
      width: 215px;
      color: black;
    }

    .container {
      position: relative;
      /* Ensure positioning context for absolute children */
      max-width: fit-content;
      margin: 150px auto 0;
      /* Center horizontally */
      background-color: rgb(12, 67, 99);
      padding: 30px 100px;
    }

    #title h2 {
      margin-top: 0;
      font-size: 42px;
      margin-bottom: 0;
    }

    #title h3 {
      margin-bottom: 40px;
      text-align: center;
    }

    .butons {
      color: black;
      width: 144px;
      padding: 12px;
      margin: 40px 0 12px;
      background-color: white;
      border: none;
      font-weight: 900;
      text-align: center;
      cursor: pointer;
      display: inline-block;
    }

    .butons:hover {
      background-color: rgb(0, 255, 0);
      color: black;
    }

    .button button:hover {
      background-color: rgb(233, 0, 0);
      color: white;
    }

    #error {
      text-align: center;
      color: rgb(255, 255, 255);
      z-index: 1;
      /* Ensure the error message is above the background */
      position: relative;
      /* Ensure the z-index takes effect */
      margin-bottom: 50px;
      font-size: x-large;
      <?php
      if (isset($_GET['WRP']) && $_GET['WRP'] == 1) {
        echo "display: block";
      } else {
        echo "display: none";
      }
      ?>
    }

    .errorBackground {
      position: absolute;
      z-index: 0;
      height: 75px;
      background-color: rgb(255, 31, 31);
      width: 500px;
      left: 50%;
      transform: translateX(-50%);
      top: 0;
      <?php
      if (isset($_GET['WRP']) && $_GET['WRP'] == 1) {
        echo "display: block";
      } else {
        echo "display: none";
      }
      ?>
      /* Position at the top of the container */
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="errorBackground"></div>
    <div class="error">
      <h3 id="error">Wrong Username/Password</h3>
    </div>
    <div id="title">
      <h2>The Eagle's Nest</h2>
      <h3>Login to continue!</h3>
    </div>
    <form action="./security/authenticate.php" method="post">
      <div id="cnic">
        <span><b>CNIC</b></span>
        <input type="text" name="cnic0" id="cnic0" class="cnic" maxlength="5" oninput="validateInput(this)" required>
        -
        <input type="text" name="cnic1" id="cnic1" class="cnic" maxlength="7" oninput="validateInput(this)" required>
        -
        <input type="text" name="cnic2" id="cnic2" class="cnic" maxlength="1" oninput="validateInput(this)" required>
      </div>
      <div id="pass">
        <span><b>Password</b></span>
        <input type="password" name="pswd" required>
      </div>
      <div class="button">
        <input class="butons" type="submit" value="Login">
        <button type="button" onclick="window.location.href='./resetPassword.php'" class="butons">
          Forgot Password
        </button>
      </div>
    </form>
  </div>
  <script>
    function validateInput(input) {
      // Remove non-numeric characters
      input.value = input.value.replace(/\D/g, '');

      // Enforce max length
      const maxLength = input.maxLength;
      if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
      }
    }
  </script>
</body>

</html>