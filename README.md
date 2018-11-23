
<p align="center">
  <img src="/img/logo2.png">
</p>

# Info
This repo shows you most of the ways to use this <a href="https://github.com/BitcoinNova/bitcoinnova-walletd-rpc-php/">library</a>
But it can also be used to send and receive transactions and the other stuff on all plattforms.
# Requirements
Requirements: composer, webserver with php 7.2 php7.2-mbstring php7.2-gd php-fpm and the <a href="https://github.com/BitcoinNova/bitcoinnova/releases">Bitcoinnova-service wallet</a>.
# Installation
clone this repository into the /var/www/html(Linux) directory or in the htdocs directory(Windows, Mac);
The the easiest way to install all librarys for php is running this command: <code>composer require chillerlan/php-qrcode bitcoinnova/bitcoinnova-walletd-rpc-php</code>
# Using
You are now ready to visit your webserver and do your stuff, but before that we have to start the wallet daemon.
You don't have a wallet? run on terminal/cmd<code>./Bitcoinnova-service -g -w walletname</code> on Linux/Mac and <code>Bitcoinnova-service.exe -g -w walletname</code> on Windows.
Have a wallet already(or generated one yet)? you'r on the target line, just run
<code>./Bitcoinnova-service -w walletname --rpc-password thestrongestpasswordeversonoonecancrackit --daemon-address pool.bitcoinnova.org</code> on Linux/Mac
and <code>Bitcoinnova-service.exe -w walletname --rpc-password thestrongestpasswordeversonoonecancrackit --daemon-address pool.bitcoinnova.org</code> on Windows.
# Be Happy
Now you are finished, you can visit you webserver under localhost and have fun with Bitcoin nova! Errors occuring with mb_internal_encoding() can be fixed for Windows <a href="http://www.php.net/manual/en/mbstring.installation.php">here</a>. Linux just type <code>apt-get install php7.2-mbstring</code>. If you still get one error: fix on Windows <a href="http://php.net/manual/en/image.installation.php">here</a>. Linux just type <code>apt-get install php-gd</code>.
