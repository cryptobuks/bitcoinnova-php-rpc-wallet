<?php
session_start();

#Requirements
require 'vendor/autoload.php';
use chillerlan\QRCode\QRCode;

#Check conf
if (!isset($_SESSION["passconf"]) || !isset($_SESSION["ipconf"]) || !isset($_SESSION["portconf"])) {
  header('Location: login.php');
}

#Enable conf
use bitcoinnova\Walletd;

$config = [
    'rpcHost'     => $_SESSION["ipconf"],
    'rpcPort'     => intval($_SESSION["portconf"]),
    'rpcPassword' => $_SESSION["passconf"],
];
$walletd = new Walletd\Client($config);

#JSON request
$addrs = $walletd->getAddresses()->getBody()->getContents();
$vkey = $walletd->getViewKey()->getBody()->getContents();
$bal = $walletd->getBalance()->getBody()->getContents();
//$seed = $walletd->getMnemonicSeed()->getBody()->getContents();

#Decode
$decaddrs = json_decode($addrs, true);
$decvkey = json_decode($vkey, true);
$decbal = json_decode($bal, true);
//$mseed = json_decode($seed, true);

#Wallet addresses out of array
$addresses = $decaddrs["result"]["addresses"];
//$demonic = $mseed ["result"]["address"];
$fcount = count($decaddrs["result"]["addresses"]);
#Balances
$balance = intval($decbal["result"]["availableBalance"]) / 100;
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
    <br /><br /><br />
    <p><a href="index.php"><img height="4%" width="4%" src="img/back.png" alt="Back"></a></p>
    <h3></h3>
    <br />
    <center>
<?php
#Check if showkeys is enabled
if (isset($_GET["showkeys"])) {
  echo '<form action="address.php" method="get">
    <input type="submit" value="Hide secret keys">
  </form>';
}
else {
#Show action to show secret keys
echo '<h3><form action="address.php" align="center" method="get">
  Generate big qr code</h3><input type="checkbox" name="sbqr">
  <input type="hidden" name="showkeys" value="true">
  <input type="submit" value="Show secret keys"><br />
</form>';
}
for ($i=0; $i < $fcount; $i++) {
  #Output all addresses with balance and qrcode
  $bal = $walletd->getBalance($addresses[$i])->getBody()->getContents();
  $decbal = json_decode($bal, true);
  $balance = intval($decbal["result"]["availableBalance"]) / 100;
  $lbalance = intval($decbal["result"]["lockedAmount"]) / 100;
  #Check if keys should be shown
  if (isset($_GET["showkeys"])) {
    #Get spendkeys for each address and output them
    $spendkey = $walletd->getSpendKeys($addresses[$i])->getBody()->getContents();
    $decspendkey = json_decode($spendkey, true);
    echo
    "<h3><br>Public address:<br> <br /> <input id='copy" . $i . "' type='text' value='" . $addresses[$i] . "' size='100%' readonly>" . "<button id='btn" . $i . "' onclick='copy" . $i . "()'>Copy</button>" .
    " <br /> <br>Balance: " . $balance . ", Locked: " . $lbalance .

    " <br /> <br>Public spend key: " . $decspendkey["result"]["spendPublicKey"] .

    " <br /> <br>Private spend key: " . $decspendkey["result"]["spendSecretKey"] .

    " <br /> <br>Private view key: " . $decvkey["result"]["viewSecretKey"] . "<br> <br /> ";

    //" <br /> <br>Mnemonic Seed: " . $demonic["result"]["address"] . "<br> <br /> ";
    echo "<script>function copy" . $i . "(){var copyText = document.getElementById('copy" . $i . "'); copyText.select(); document.execCommand('Copy'); document.getElementById('btn" . $i . "').innerHTML = 'Copied!'}</script>";
    #Check if a qr code with all keys should be generated
    if (isset($_GET["sbqr"])) {
      $big = "pubaddr:" . $addresses[$i] . ";pubspend:" . $decspendkey["result"]["spendPublicKey"] . ";privspend:" . $decspendkey["result"]["spendSecretKey"] . ";privview:" . $decvkey["result"]["viewSecretKey"] . ";";
      echo '<img style="background-color: #fff;" src="'.(new QRCode)->render($big).'" />';
    }
    else {
      echo '<br><img style="background-color: #fff;" src="'.(new QRCode)->render($addresses[$i]).'" />';
    }
  }
  else {
    #Output without keys
    echo "<h3><br>Public address:<br>  <br /> <input id='copy" . $i . "' type='text' value='" . $addresses[$i] . "' size='100%' readonly>" . "<button id='btn" . $i . "' onclick='copy" . $i . "()'>Copy</button>";
    echo "<script>function copy" . $i . "(){var copyText = document.getElementById('copy" . $i . "'); copyText.select(); document.execCommand('Copy'); document.getElementById('btn" . $i . "').innerHTML = 'Copied!'}</script>";
    echo " <br /> <br>Balance: " . $balance . ", Locked: " . $lbalance . "<br> <br /> " . '<img style="background-color: #fff;" src="'.(new QRCode)->render($addresses[$i]).'" />';
  }
}
?>
</div>
</div>
  </body>
</html>
