## PHP-FPM Image (Dockerfile-php-fpm)
FROM php-base AS php-fpm
#----------------------------------------------------------------
##
ENV PATH="/usr/local/bin:${PATH}"
#----------------------------------------------------------------
## Copy PHP configuration (from the base image)
COPY --from=php-base /usr/local/etc/php /usr/local/etc/php
#----------------------------------------------------------------
## Install PHP-FPM and other necessary runtime dependencies
#RUN apk add --no-cache  \
#    php${PHP_VERSION/-/\.}-fpm \
#    php${PHP_VERSION/-/\.}-opcache \
#    php${PHP_VERSION/-/\.}-redis \
#    php${PHP_VERSION/-/\.}-xdebug;
RUN apk add --no-cache  \
    php-fpm \
    php-opcache \
    php-redis \
    php-xdebug;
#----------------------------------------------------------------
## Add PHP-FPM pool configuration
COPY ./.docker/php/fpm/conf.d/www.conf  /etc/php82/php-fpm.d/www.conf
#----------------------------------------------------------------
## Copy the Composer dependencies
COPY ./www/composer.json  $APP_CODE_PATH
COPY ./www/composer.lock $APP_CODE_PATH
#----------------------------------------------------------------
## Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
#----------------------------------------------------------------
## Run the Composer without interaction and additional scripts
RUN composer install --no-interaction --optimize-autoloader --no-scripts
#----------------------------------------------------------------
##
USER $APP_USER_NAME
#----------------------------------------------------------------
## Set working directory
ARG APP_CODE_PATH="/var/www/app"
WORKDIR "$APP_CODE_PATH"
#----------------------------------------------------------------
# Expose port 9000 for PHP-FPM
EXPOSE 9000
#----------------------------------------------------------------
CMD ["/usr/sbin/php-fpm82", "-F"]