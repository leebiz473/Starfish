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

# set up ssh
RUN apt-get update -yqq && apt-get install -yqq openssh-server \
 && mkdir /var/run/sshd \
;

# add default public key to authorized_keys
USER ${APP_USER}
COPY ./.docker/php/app/.ssh/appkey.pub /tmp/appkey.pub
RUN mkdir -p ~/.ssh \
 && cat /tmp/appkey.pub >> ~/.ssh/authorized_keys \
 && chown -R ${APP_USER}: ~/.ssh \
 && chmod 700 ~/.ssh \
 && chmod 600 ~/.ssh/authorized_keys \
;

USER root

## php config
COPY ./.docker/php/conf.d/*  /usr/local/etc/php/conf.d/

## Set Workdir
ARG APP_CODE_PATH="/var/www/app"
WORKDIR "$APP_CODE_PATH"

## Add custom entrypoint
RUN mkdir -p /bin/docker-entrypoint/ \
    && cp /tmp/scripts/docker-entrypoint/* /bin/docker-entrypoint/ \
    && chmod +x -R /bin/docker-entrypoint \
    ;

## Cleanup build
RUN /tmp/scripts/cleanup.sh

## Change message of the day
RUN echo "Today is $(date "+%A, %B %d, %Y %H:%M:%S")" > /etc/motd

# @see https://docs.docker.com/engine/examples/running_ssh_service/
ENTRYPOINT ["/bin/docker-entrypoint/resolve-docker-host-ip.sh", "/usr/sbin/sshd", "-D"]