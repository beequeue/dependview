# Base image
FROM php:7.0.8-apache

# Set working directory
WORKDIR /var/www

# Update list of the repositories for available updates and install needed packages
RUN apt-get update \
    && apt-get install -y git zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Install XDebug
RUN pecl install -f xdebug-2.4.0 \
    && pecl clear-cache \
    && echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini

# Clear directory and prep Apache - remove default vhost as ours is in apache2.conf
RUN chown www-data:www-data /var/www \
    && rm -r /var/www/* \
    && a2dissite 000-default \
    && a2enmod expires

# Transfer config files to container
ADD docker-config/apache2.conf /etc/apache2/apache2.conf
ADD docker-config/php-app.ini /usr/local/etc/php/conf.d/php-app.ini

# Copy files to container and install app
COPY . /var/www/

RUN composer self-update

RUN cd /var/www && \
    php -n $(which composer) install --working-dir=/var/www --no-interaction --no-progress

RUN chown -R www-data:www-data /var/www
