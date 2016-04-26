#!/usr/bin/env bash

# Prevent TTY Errors
#
# Technically this works to prevent TTY errors, but it also seemes to break
# vagrant ssh -c "":
# config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"
#
# And the following works but throws some odd errors as well:
# https://github.com/mitchellh/vagrant/issues/1673#issuecomment-34040409
# (grep -q -E '^mesg n$' /root/.profile && sed -i 's/^mesg n$/tty -s \\&\\& mesg n/g' /root/.profile && echo 'Ignore the previous error about stdin not being a tty. Fixing it now...') || exit 0;
#
# So we use this instead:
# http://serverfault.com/questions/500764/dpkg-reconfigure-unable-to-re-open-stdin-no-file-or-directory
export LANGUAGE=en_US.UTF-8
export LANG=en_US.UTF-8
export LC_ALL=en_US.UTF-8
locale-gen en_US.UTF-8
dpkg-reconfigure locales

# Set the Breeze Environment Variable
source /home/vagrant/.profile && [ -z "$BREEZE_ENV" ] && echo "export BREEZE_ENV=1" >> /home/vagrant/.profile

# Update Package List
apt-get update

# Update System Packages
# apt-get -y upgrade

# Add the breeze executable to our path
ln -s /var/breeze/breeze /usr/local/bin

# Install PHP
apt-get install -y php5-cli php5-dev php-pear \
php5-mysqlnd php5-pgsql php5-sqlite \
php5-apcu php5-json php5-curl php5-gd \
php5-gmp php5-imap php5-mcrypt php5-xdebug \
php5-memcached

# Make MCrypt Available
ln -s /etc/php5/conf.d/mcrypt.ini /etc/php5/mods-available
sudo php5enmod mcrypt

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Add Composer Global Bin To Path
printf "\nPATH=\"/home/vagrant/.composer/vendor/bin:\$PATH\"\n" | tee -a /home/vagrant/.profile

# Install GIT
apt-get install -y git

# Install MySQL
debconf-set-selections <<< "mysql-server mysql-server/root_password password secret"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password secret"
apt-get install -y mysql-server-5.6

# Configure MySQL Remote Access
sed -i '/^bind-address/s/bind-address.*=.*/bind-address = 0.0.0.0/' /etc/mysql/my.cnf
mysql --user="root" --password="secret" -e "GRANT ALL ON *.* TO root@'0.0.0.0' IDENTIFIED BY 'secret' WITH GRANT OPTION;"
service mysql restart

# Install Apache2
apt-get install apache2 -y

# Add vagrant user To www-data
usermod -a -G www-data vagrant
id vagrant
groups vagrant

# Run apache as vagrant
sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/' /etc/apache2/envvars
sed -i 's/APACHE_RUN_GROUP=www-data/APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars

# Enable apache modules
apt-get -y install libapache2-mod-php5
a2enmod rewrite
a2enmod expires
a2enmod headers
a2enmod include
a2enmod proxy
a2enmod proxy_http
a2enmod php5
a2enmod rewrite
a2enmod status
a2enmod ssl

# Restart apache
service apache2 restart

# Add breeze autocompletion
printf "\neval \$(breeze _completion --generate-hook --program breeze)\n" | tee -a /home/vagrant/.profile
