<?php
#Start session
session_start();

if (isset($_GET["logout"])) {
  session_destroy();
  header('Location: index.php');
}
#Load libs
require 'vendor/autoload.php';

#Check Session
if (!isset($_SESSION["passconf"]) || !isset($_SESSION["ipconf"]) || !isset($_SESSION["portconf"])) {
  header('Location: login.php');
}
/*
if (!isset($_SESSION["thistory"])) {
  $_SESSION["thistory"] = array(0 => "null");
}
*/

#Config
use bitcoinnova\Walletd;

$config = [
    'rpcHost'     => $_SESSION["ipconf"],
    'rpcPort'     => intval($_SESSION["portconf"]),
    'rpcPassword' => $_SESSION["passconf"],
];
$walletd = new Walletd\Client($config);

#JSON responses
$status = $walletd->getStatus()->getBody()->getContents();
$bal = $walletd->getBalance()->getBody()->getContents();

#Decode
$decstats = json_decode($status, true);
$decbal = json_decode($bal, true);
$decstats = json_decode($status, true);

#Balances
$balance = intval($decbal["result"]["availableBalance"]) / 100;
$lbalance = intval($decbal["result"]["lockedAmount"]) / 100;

#Stats
$sblocks = $decstats["result"]["blockCount"];
$bcount = $decstats["result"]["knownBlockCount"];

#Market value
$usd = '##';
$response = json_decode(file_get_contents($usd, false), true);
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="description" content="bitcoinnovaWebWallet">
    <meta name="author" content="@crappyrules">
    <link rel="icon" href="img/favicon.ico">

    <title>BTN PHP Wallet </title>

    <!-- Bootstrap core CSS-->
    <link href="css/bootstrap-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">

  </head>
  <body>
    <div class="container">
    <p>
      <center>
      <img src="img/logo.png" alt="bitcoinnova">
    </center>
    </p>
    </div>
    <br />
    <div class="container-fluid">
  <h2>Your available balance is: <?php echo $balance . " BTN" . " (" . $balance * $response["result"][0]["price"] . " $)"; ?> </h2>
  <h3><p>Your locked balance is: <?php echo $lbalance . " BTN" . " (" . $lbalance * $response["result"][0]["price"] . " $)"; ?></p>
  <p>Daemon status: <?php echo $sblocks . " of " . $bcount . " blocks synced"; ?></p>
   <div class="row">
     <div class="col"><a href="transact.php"><img src="img/transfer.png" alt="Send BTN"></a></div>
     <div class="col"><a href="address.php"><img src="img/addresses.png" alt="Show Addresses"></a></div>
     <div class="col"><a href="maintain.php"><img src="img/create.png" alt="Create/Destroy Addresses"></a></div>
     <div class="col"><a href="history.php"><img src="img/history.png" alt="Transaction history"></a></div>
     </div>
     <div class="row">
       <div class="col"><caption>Make a transaction</caption></div>
       <div class="col"><caption>Show Addresses</caption></div>
       <div class="col"><caption>Create/Destroy Addresses</caption></div>
       <div class="col"><caption>Transaction History</caption></div>
     </div>
     <br /><br /><br /><br /><br />
     <div class="row">
     <div class="col" align="center"><a href="index.php?logout=true"><img src="img/logout.png" alt="Logout"></a></div>
   </div>
   <div class="row">
     <div class="col" align="center"><caption>Logout</caption></div>
  </div>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
  if (!Notification) {
    alert('Desktop notifications not available in your browser.');
    return;
  }
  if (Notification.permission !== "granted")
    Notification.requestPermission();
});
    </script>
  </body>
</html>
