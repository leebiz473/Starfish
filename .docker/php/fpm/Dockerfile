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
RUN apk add --no-cache  \
    gcc \
    make \
    libc-dev \
    pkgconfig \
    linux-headers \
    php${PHP_INSTALLED_VERSION}-dev \
    php${PHP_INSTALLED_VERSION}-pear \
    php${PHP_INSTALLED_VERSION}-fpm \
    php${PHP_INSTALLED_VERSION}-opcache \
    php${PHP_INSTALLED_VERSION}-redis;
#----------------------------------------------------------------
## Install xdebug
RUN pecl install xdebug;
#----------------------------------------------------------------
## Add PHP-FPM pool configuration
COPY ./.docker/php/fpm/conf.d/www.conf /etc/php82/php-fpm.d/www.conf
COPY ./.docker/php/conf.d/xdebug.ini /etc/php82/conf.d/50_xdebug.ini
#----------------------------------------------------------------
## Set working directory (as root initially)
WORKDIR $APP_CODE_PATH
#----------------------------------------------------------------
## Copy the Composer dependencies (as root)
COPY ./www/composer.json  $APP_CODE_PATH
COPY ./www/composer.lock $APP_CODE_PATH
#----------------------------------------------------------------
## Set correct ownership **before switching users**
RUN chown -R $APP_USER_NAME:$APP_GROUP_NAME $APP_CODE_PATH
#----------------------------------------------------------------
## Switch to non-root user **after setting ownership**
USER $APP_USER_NAME
#----------------------------------------------------------------
## Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
#----------------------------------------------------------------
## Run the Composer without interaction and additional scripts (as non-root)
RUN composer install --no-interaction --optimize-autoloader --no-scripts
#----------------------------------------------------------------
## Set ownership and permissions
RUN chown -R ${APP_USER_NAME}:${APP_GROUP_NAME} ${APP_CODE_PATH}
RUN chown -R ${APP_USER_NAME}:${APP_GROUP_NAME} ${APP_CODE_PATH}/vendor
RUN chmod -R 775 ${APP_CODE_PATH}
RUN chmod -R 775 ${APP_CODE_PATH}/vendor
#----------------------------------------------------------------
# Expose port 9000 for PHP-FPM
EXPOSE 9000

USER root
#----------------------------------------------------------------
CMD ["/usr/sbin/php-fpm82", "-F"]