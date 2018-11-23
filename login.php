<?php
#Start session
session_start();
#Check session
if (isset($_SESSION["passconf"]) && isset($_SESSION["ipconf"]) && isset($_SESSION["portconf"])) {
  header('Location: index.php');
}
#Check for request
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
  $_SESSION["passconf"] = $_POST["password"];
  $_SESSION["ipconf"] = $_POST["ip"];
  $_SESSION["portconf"] = $_POST["port"];
  header('Location: index.php');
}
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="description" content="bitcoinnovaWebWallet">
    <meta name="author" content="@crappyrules @NicoAlAv">
    <link rel="icon" href="img/favicon.ico">

    <title>BTN PHP Wallet </title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>
  <body>
   <div class="container">
   <p>
     <center>
     <img src="img/logo.png" alt="bitcoinnova">
   </center>
   </p>
   </div>

   <div class="container">

     <form id="loginform" name="loginForm" method="post" action="login.php" class="form-signin">
       <h2 class="form-signin-heading">Please Sign in</h2>

   <?php include('errors.php'); ?>

       <label for="ip" class="label">RPC Server</label>
       <input name="ip" type="url" class="form-control" id="ip" placeholder="http://127.0.0.1" />
       <label for="port" class="label">RPC Port</label>
       <input name="port" type="number" class="form-control" id="port" placeholder="8070" />
       <label for="password" class="label">Password</label>
       <input name="password" type="password" class="form-control" id="password" placeholder="Password" />
       <input class="btn btn-lg btn-primary btn-block" type="submit" name="login" value="Login" />
     </form>
     </div>

</body>
</html>
