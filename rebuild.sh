#!/usr/bin/env bash

chown drupaluser:www-data /var/www/html/ -R
chown www-data:www-data /var/www/html/sites/default -R

/etc/init.d/ssh start
apachectl start
cd
drush config-set system.site uuid shoppinglist-back
# drush cim --yes
drush cr
apachectl restart
tail -f /var/log/apache2/error.log
