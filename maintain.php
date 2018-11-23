<?php
require 'vendor/autoload.php';

session_start();

use chillerlan\QRCode\QRCode;

if (!isset($_SESSION["passconf"]) || !isset($_SESSION["ipconf"]) || !isset($_SESSION["portconf"])) {
  header('Location: login.php');
}
use bitcoinnova\Walletd;

$config = [
    'rpcHost'     => $_SESSION["ipconf"],
    'rpcPort'     => intval($_SESSION["portconf"]),
    'rpcPassword' => $_SESSION["passconf"],
];
$walletd = new Walletd\Client($config);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Transaction</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <script src="js/transact.js"></script>
  </head>
  <body>
    <div class="container">
    <p>
      <center>
      <img src="img/logo.png" alt="bitcoinnova">
    </center>
    </p>
    </div>
    <br /><br /><br />
    <p><a href="index.php"><img height="4%" width="4%" src="img/back.png" alt="Back"></a></p>
    <h3></h3>
    <br />
    <center>
    <h3>Create address
      <br />
      <br />
    <form action="maintain.php" method="post">
      <input type="hidden" name="method" value="gen">
      <input type="submit" value="Generate">
    </form>
    <br />
    Delete address <mark>!WARNING!</mark>: You can only restore your wallet with the public and private spend key on command line zedwallet!
      <br />
      <br />
      <form action="maintain.php" method="post">
      <input type="hidden" name="method" value="del">
      <input type="text" name="addr" size="85%" placeholder="Address to delete">
      <input type="submit" value="Delete">
    </form>
    <br />
    <br />
    <?php
    #Check request method
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      #Check what to exec
      if ($_POST["method"] == "gen") {
        #Generate new address
        $gen = $walletd->createAddress()->getBody()->getContents();
        #decode
        $decgen = json_decode($gen, true);
        #Show address with qr code
        $naddr = $decgen["result"]["address"];
        echo $naddr;
        echo ' <br /> <br><img style="background-color: #fff" src="'.(new QRCode)->render($naddr).'" />';
      }
      elseif ($_POST["method"] == "del") {
        $bal = $walletd->getBalance($_POST["addr"])->getBody()->getContents();

        $decbal = json_decode($bal, true);

        $balance = intval($decbal["result"]["availableBalance"]) / 100;
        if ($balance != 0) {
          echo '<script>var confirm = prompt("Please type DELETE to delete ' . substr($_POST["addr"], 0, -45) . '... with an balance of ' . $balance . ' BTN",""); if (confirm != "DELETE") {alert("Action cancelled");} else {window.location = "maintain.php?c=true&addr=' . $_POST["addr"] . '";}</script>';
        }
        else {
          echo '<script>var confirm = prompt("Please type DELETE to delete ' . substr($_POST["addr"], 0, -45) . '... with a zero BTN balance",""); if (confirm != "DELETE") {alert("Action cancelled");} else {window.location = "maintain.php?c=true&addr=' . $_POST["addr"] . '";}</script>';
        }
    }
  }
  elseif (isset($_GET["c"])) {
    #Delete address
    $resp = $walletd->deleteAddress($_GET["addr"])->getBody()->getContents();
    #Decode
    $decresp = json_decode($resp, true);
    #Check for errors
    if (isset($decresp["error"])) {
      echo "<script>alert('The address is invalid, or doesn\'t exists!')</script>";
    }
    else {
      echo "<script>alert('Address deleted!')</script>";
    }
  }
     ?>
  </body>
</html>
