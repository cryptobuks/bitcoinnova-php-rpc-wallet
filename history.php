<?php
#Load libs
require 'vendor/autoload.php';

#Start session
session_start();

#Check Session
if (!isset($_SESSION["passconf"]) || !isset($_SESSION["ipconf"]) || !isset($_SESSION["portconf"])) {
  header('Location: login.php');
}
#Config
use bitcoinnova\Walletd;

$config = [
    'rpcHost'     => $_SESSION["ipconf"],
    'rpcPort'     => intval($_SESSION["portconf"]),
    'rpcPassword' => $_SESSION["passconf"],
];

$walletd = new Walletd\Client($config);

$status = $walletd->getStatus()->getBody()->getContents();
$uctrans = $walletd->getDelayedTransactionHashes()->getBody()->getContents();
$addrs = $walletd->getAddresses()->getBody()->getContents();

$decstats = json_decode($status, true);
$decaddrs = json_decode($addrs, true);
$decuctrans = json_decode($uctrans, true);

$addresses = $decaddrs["result"]["addresses"];
$fcount = count($decaddrs["result"]["addresses"]);
$uctcount = count($decuctrans["result"]["transactionHashes"]);
$baddrs = array();
$bcount = intval($decstats["result"]["knownBlockCount"]);
$fbi = 1;
 ?>
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
     <h3>
     <br />
     <center>
    Sent transactions:<br>
    <iframe src="listtrans.php" frameborder='0' width="102%"></iframe>
   </p>Transactions you didn't confirm<br>
     <?php
     for ($i=0; $i < $uctcount; $i++) {
       $yeslink = 'javascript:window.location = "transact.php?send=' . $decuctrans["result"]["transactionHashes"][$i] . '"';
       $nolink = 'javascript:window.location = "transact.php?cancel=' . $decuctrans["result"]["transactionHashes"][$i] . '"';
       echo $decuctrans["result"]["transactionHashes"][$i] . "<button onclick='" . $yeslink . "'>Confirm</button><button onclick='" . $nolink . "'>Cancel</button>";
     }
     if ($uctcount == 0) {
       echo "<span>No transactions found!</span>";
     }
      ?>
  </body>
</html>
