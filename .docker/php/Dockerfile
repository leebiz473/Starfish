# Use the official PHP image with Composer pre-installed
FROM php:8.2-fpm

# Install necessary extensions and debugging tools
RUN apt-get update && apt-get install -y \
    bash \
    curl \
    vim \
    net-tools \
    git \
    unzip \
    iputils-ping \
    && pecl install xdebug \
    && pecl install mongodb \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable mongodb \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the working directory inside the container
WORKDIR /var/www/app

# Add healthcheck and ensure that it is excuteable
COPY .docker/php/health-check.sh /usr/local/bin/health-check.sh
RUN chmod +x /usr/local/bin/health-check.sh

# Add PHP configs
COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN chmod +x /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY .docker/php/mongo.ini /usr/local/etc/php/conf.d/docker-php-ext-mongo.ini
RUN chmod +x /usr/local/etc/php/conf.d/docker-php-ext-mongo.ini

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Use PHP-FPM as the main process
CMD ["php-fpm"]