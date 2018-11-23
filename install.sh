sudo apt install ca-certificates apt-transport-https
wget -q https://packages.sury.org/php/apt.gpg -O- | sudo apt-key add -
echo "deb https://packages.sury.org/php/ stretch main" | sudo tee /etc/apt/sources.list.d/php.list
sudo apt install -y composer php7.2 php7.2-mbstring php7.2-gd php-fpm
sudo cp -r . /var/www/html
cd /var/www/html
composer require chillerlan/php-qrcode turtlecoin/turtlecoin-walletd-rpc-php
sudo rm install.sh
echo "all done!"
