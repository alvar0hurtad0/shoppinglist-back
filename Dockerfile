FROM drupal:8.1

RUN apt-get update && apt-get -y install git-all php5-curl mysql-client openssh-server wget
RUN pecl install uploadprogress
# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# Install Drush 8.
RUN wget http://files.drush.org/drush.phar \
 && chmod +x drush.phar \
 && mv drush.phar /usr/local/bin/drush

COPY assets/settings.php /var/www/html/sites/default/settings.php

RUN usermod -u 1001 www-data \
    && chown www-data:www-data /var/www/html/sites/default/settings.php \
    && mkdir -p /var/www/html/sites/default/files/translations \
    && chown -R www-data:www-data /var/www/html/sites/default/ \
    && mkdir /var/www/configuration \
    && chown -R www-data:www-data /var/www/configuration

#ssh acces to allow drush alias access
RUN useradd -d /var/www drupaluser -u 1000 -G www-data -s /bin/bash
COPY assets/ssh/sshd_config /etc/ssh/sshd_config
RUN mkdir -p /var/www/.ssh
COPY assets/ssh/authorized_keys /var/www/.ssh/
RUN chmod 700 /var/www/.ssh/ -R
RUN chmod 600 /var/www/.ssh/*
RUN chown drupaluser /var/www/.ssh/ -R

# Configure services
COPY assets/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf
# add modules, themes and libraries
ADD libraries /var/www/html/libraries
ADD profile /var/www/html/profiles/custom_profile
ADD configuration /var/www/configuration

ARG DEVELOPMENT
RUN if [ ${DEVELOPMENT} -eq 1 ] ; then \
  pecl install -o -f xdebug \
  && rm -rf /tmp/pear \
  && echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_enable=on"  >> /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_host=172.17.42.1" >> /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "xdebug.remote_connect_back=On" >> /usr/local/etc/php/conf.d/xdebug.ini \
  && echo "memory_limit = 64M" > /usr/local/etc/php/conf.d/php.ini ; \
fi

ADD rebuild.sh /root/rebuild.sh
CMD chmod +x /root/rebuild.sh && sh /root/rebuild.sh
