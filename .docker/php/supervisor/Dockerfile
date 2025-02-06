## PHP-Queue-Worker Image (Dockerfile-php-worker)
FROM php-fpm AS php-supervisor
#----------------------------------------------------------------
## Copy the installed Composer dependencies from the php-fpm stage
COPY --from=php-fpm /var/www/app /var/www/app
#----------------------------------------------------------------
##
ENV PATH="/usr/local/bin:${PATH}"
#----------------------------------------------------------------
## Set user
## Set working directory (as root initially)
USER root
WORKDIR $APP_CODE_PATH
#----------------------------------------------------------------
## Set correct ownership **before switching users**
RUN chown -R $APP_USER_NAME:$APP_GROUP_NAME $APP_CODE_PATH
#----------------------------------------------------------------
## Install Supervisor and necessary dependencies
RUN apk update && \
    apk add --no-cache supervisor

#----------------------------------------------------------------
## Create the necessary directories for Supervisor
#RUN mkdir -p /etc/supervisor/conf.d /var/log/supervisor
#----------------------------------------------------------------
## Copy Supervisor configuration file into the container
COPY ./.docker/php/supervisor/conf.d/supervisord.conf /etc/supervisor/supervisord.conf

## Switch to non-root user **after setting ownership**
USER $APP_USER_NAME

EXPOSE 9001

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]