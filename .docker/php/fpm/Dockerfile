ARG TARGET_PHP_VERSION=8.2
FROM php:${TARGET_PHP_VERSION}-fpm

## Copy build scripts and ensure build scripts are executable
COPY ./.docker/.shared/ /tmp/scripts/
RUN chmod -R 777 /tmp/scripts/ && chmod +x /tmp/scripts/

## Set timezone
ARG TZ=GMT
RUN /tmp/scripts/set-timezone.sh ${TZ}

## Add users
ARG APP_USER=www-data
ARG APP_GROUP=www-data
ARG APP_USER_ID=1000
ARG APP_GROUP_ID=1000

##
RUN /tmp/scripts/create-user.sh ${APP_USER} ${APP_GROUP} ${APP_USER_ID} ${APP_GROUP_ID}
RUN /tmp/scripts/install-php-extensions.sh
RUN /tmp/scripts/install-software.sh

## php config
COPY ./.docker/php/conf.d/*  /usr/local/etc/php/conf.d/

## php-fpm pool config
COPY ./.docker/php/fpm/conf.d/* /usr/local/etc/php-fpm.d
RUN /tmp/scripts/modify-config.sh /usr/local/etc/php-fpm.d/fpm-app.conf \
    "__APP_USER" \
    "${APP_USER}" \
 && /tmp/scripts/modify-config.sh /usr/local/etc/php-fpm.d/fpm-app.conf \
    "__APP_GROUP" \
    "${APP_GROUP}" \
;

## Set Workdir
ARG APP_CODE_PATH="/var/www/app"
WORKDIR "$APP_CODE_PATH"

## Cleanup build
RUN /tmp/scripts/cleanup.sh

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Use PHP-FPM as the main process
CMD ["php-fpm"]