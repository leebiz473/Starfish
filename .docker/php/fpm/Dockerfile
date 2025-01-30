## PHP-FPM Image (Dockerfile-php-fpm)
FROM php-base AS php-fpm
#----------------------------------------------------------------
##
ENV PATH="/usr/local/bin:${PATH}"
#----------------------------------------------------------------
## Copy PHP configuration (from the base image)
COPY --from=php-base /usr/local/etc/php /usr/local/etc/php
#----------------------------------------------------------------
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
#----------------------------------------------------------------
## Add PHP-FPM pool configuration
#COPY ./.docker/php/fpm/conf.d/*  /usr/local/etc/php/conf.d/
#----------------------------------------------------------------
#ARG PHP_VERSION
#----------------------------------------------------------------
## Modify listen directive in the www.conf file
#RUN sed -i "s|^listen =.*|listen = /var/run/php-fpm.sock|" /etc/php/www.conf && \
#    sed -i "s|^listen.owner =.*|listen.owner = nginx|" /etc/php/www.conf && \
#    sed -i "s|^listen.group =.*|listen.group = nginx|" /etc/php/www.conf && \
#    sed -i "s|^listen.mode =.*|listen.mode = 0660|" /etc/php/www.conf
#----------------------------------------------------------------
# /etc/php82/php-fpm.d/www.conf
#----------------------------------------------------------------
## Set working directory
ARG APP_CODE_PATH="/var/www/app"
WORKDIR "$APP_CODE_PATH"
#----------------------------------------------------------------
# Expose port 9000 for PHP-FPM
EXPOSE 9000
#----------------------------------------------------------------
CMD ["/usr/sbin/php-fpm82", "-F"]