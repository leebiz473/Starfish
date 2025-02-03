ARG ALPINE_VERSION
FROM alpine:${ALPINE_VERSION} AS php-base

# make build args available as ENV variables to downstream images
# so that we don't have to pass the same build args again

ARG APP_ENV
ARG ALPINE_VERSION
ARG TARGET_PHP_VERSION
#----------------------------------------------------------------
ARG APP_USER_ID
ARG APP_GROUP_ID
#----------------------------------------------------------------
ARG APP_USER_NAME
ARG APP_GROUP_NAME
#----------------------------------------------------------------
ARG APP_CODE_PATH
#----------------------------------------------------------------
ENV ALPINE_VERSION=${ALPINE_VERSION}
#----------------------------------------------------------------
ENV TARGET_PHP_VERSION=${TARGET_PHP_VERSION}
#----------------------------------------------------------------
ENV APP_USER_ID=${APP_USER_ID}
ENV APP_GROUP_ID=${APP_GROUP_ID}
#----------------------------------------------------------------
ENV APP_USER_NAME=${APP_USER_NAME}
ENV APP_GROUP_NAME=${APP_GROUP_NAME}
#----------------------------------------------------------------
ENV APP_CODE_PATH=${APP_CODE_PATH}

## Copy build scripts and ensure build scripts are executable
COPY ./.docker/.shared/ /tmp/scripts/
RUN chmod -R 777 /tmp/scripts/ && chmod +x /tmp/scripts/

## Set timezone
ARG TZ=GMT
RUN /tmp/scripts/set-timezone.sh ${TZ}
#----------------------------------------------------------------
## Add users
ARG APP_USER=${APP_USER_NAME}
ARG APP_GROUP=${APP_GROUP_NAME}
ARG APP_USER_ID=${APP_USER_ID}
ARG APP_GROUP_ID=${APP_GROUP_ID}
#----------------------------------------------------------------
## Add user and groups, legacy, modern
RUN addgroup -g $APP_GROUP_ID $APP_GROUP_NAME && \
    adduser -D -u $APP_USER_ID -s /bin/bash -G $APP_GROUP_NAME $APP_USER_NAME && \
    install -d -m 755 -o $APP_USER_NAME -g $APP_GROUP_NAME $APP_CODE_PATH
#----------------------------------------------------------------
## Process and store a new environment variable without the dot
## Create a new argument without the dot
ARG PHP_VERSION
RUN PHP_VERSION=$(echo ${TARGET_PHP_VERSION} | sed 's/\.//g') && \
    echo "PHP_VERSION=${PHP_VERSION}" >> /etc/environment
#----------------------------------------------------------------
## Set PHP_VERSION as an environment variable
ENV PHP_VERSION=${PHP_VERSION}
#----------------------------------------------------------------
## Install necessary system dependencies, including ca-certificates for HTTPS
RUN apk update && \
    apk add --no-cache \
            bash \
            make \
            vim  \
            ca-certificates  \
            php${PHP_VERSION} \
            php${PHP_VERSION}-cli \
            php${PHP_VERSION}-session \
            php${PHP_VERSION}-mbstring \
            php${PHP_VERSION}-pdo \
            php${PHP_VERSION}-pdo_mysql \
            php${PHP_VERSION}-tokenizer \
            php${PHP_VERSION}-xml \
            php${PHP_VERSION}-ctype \
            php${PHP_VERSION}-json \
            php${PHP_VERSION}-openssl \
            php${PHP_VERSION}-fileinfo \
            php${PHP_VERSION}-bcmath \
            php${PHP_VERSION}-curl \
            php${PHP_VERSION}-zip \
            php${PHP_VERSION}-sodium \
            php${PHP_VERSION}-iconv \
            php${PHP_VERSION}-intl \
            php${PHP_VERSION}-phar \
            php${PHP_VERSION}-dom \
            php${PHP_VERSION}-xmlwriter \
            libxml2-dev
#----------------------------------------------------------------
# Install the PHP DOM extension (ext-dom)
RUN apk add --no-cache php${PHP_VERSION}-dom

## Ensure CA certificates are up-to-date
RUN update-ca-certificates
#----------------------------------------------------------------
## Modifies alpines default shell to use $bash instead of $ash
RUN sed -e 's;/bin/ash$;/bin/bash;g' -i /etc/passwd
#----------------------------------------------------------------
## Add PHP configuration
COPY ./.docker/php/conf.d/app.ini  /usr/local/etc/php/conf.d/
COPY ./.docker/php/conf.d/app-${APP_ENV}.ini  /usr/local/etc/php/conf.d/
#----------------------------------------------------------------
## Add bash configuration
COPY ./.docker/php/.bashrc /home/${APP_USER_NAME}/.bashrc
COPY ./.docker/php/.bashrc /root/.bashrc
#----------------------------------------------------------------
RUN apk add --no-cache --update \
        mysql-client \
        redis
#----------------------------------------------------------------
## Install necessary dependencies for Composer
RUN apk add --no-cache \
    curl \
    bash \
    git \
    unzip
#----------------------------------------------------------------
##
WORKDIR $APP_CODE_PATH
#----------------------------------------------------------------
## @todo Set permissions for Laravel storage & cache
#RUN chown -R www-app:www-app /var/www/app/storage /var/www/app/bootstrap/cache
