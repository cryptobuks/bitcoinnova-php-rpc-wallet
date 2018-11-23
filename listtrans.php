<?php
#Load libs
require 'vendor/autoload.php';

#Start session
session_start();

#Config
use bitcoinnova\Walletd;

$config = [
    'rpcHost'     => $_SESSION["ipconf"],
    'rpcPort'     => intval($_SESSION["portconf"]),
    'rpcPassword' => $_SESSION["passconf"],
];

$walletd = new Walletd\Client($config);

$status = $walletd->getStatus()->getBody()->getContents();
$addrs = $walletd->getAddresses()->getBody()->getContents();

$decstats = json_decode($status, true);
$decaddrs = json_decode($addrs, true);

$addresses = $decaddrs["result"]["addresses"];
$fcount = count($decaddrs["result"]["addresses"]);
$baddrs = array();
$bcount = intval($decstats["result"]["knownBlockCount"]);
$fbi = 1;
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
    <center>
    <?php
    for ($i=0; $i < $fcount; $i++) {
      array_push($baddrs, $addresses[$i]);
    }
    $ltrans = $walletd->getTransactions($bcount, $fbi, null, $baddrs)->getBody()->getContents();
    $decltrans = json_decode($ltrans, true);
//    print_r($decltrans["result"]["items"]);
    $pcount = count($decltrans["result"]["items"]);
    $cadd = count($baddrs);
    for ($i=0; $i < $pcount; $i++) {
      $tcount = count($decltrans["result"]["items"][$i]["transactions"][0]["transfers"]);
      for ($j=0; $j < $tcount; $j++) {
        for ($k=0; $k < $cadd; $k++) {
          if ($baddrs[$k] == $decltrans["result"]["items"][$i]["transactions"][0]["transfers"][$j]["address"]) {
            if ($decltrans["result"]["items"][$i]["transactions"][0]["transfers"][$j]["amount"] < 0) {
              echo "<h3>Outgoing: " . "<a target='_blank' href='https://turtle.land//?hash=" . $decltrans["result"]["items"][$i]["transactions"][0]["transactionHash"] . "#blockchain_transaction'>" . $decltrans["result"]["items"][$i]["transactions"][0]["transactionHash"] . "</a><br>";
            }
            else {
              echo "<h3>Incoming: " . "<a target='_blank' href='https://turtle.land//?hash=" . $decltrans["result"]["items"][$i]["transactions"][0]["transactionHash"] . "#blockchain_transaction'>" . $decltrans["result"]["items"][$i]["transactions"][0]["transactionHash"] . "</a><br>";
            }
          }
        }
      }
      //echo "<a target='_blank' href='https://turtle.land//?hash=" . $decltrans["result"]["items"][$i]["transactions"][0]["transfers"] . "#blockchain_transaction'>" . $decltrans["result"]["items"][$i]["transactions"][0]["transactionHash"] . "</a><br>";
    }
    if ($pcount == 0) {
      echo "<h3>No transactions found";
    }
     ?>
  </body>
</html>
