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
## Add user and groups, legacy, modern
RUN addgroup -g $APP_GROUP_ID $APP_GROUP_NAME && \
    adduser -D -u $APP_USER_ID -s /bin/bash -G $APP_GROUP_NAME $APP_USER_NAME && \
    install -d -m 755 -o $APP_USER_NAME -g $APP_GROUP_NAME $APP_CODE_PATH
#----------------------------------------------------------------
## Set PHP_VERSION as an environment variable
ENV PHP_VERSION=${TARGET_PHP_VERSION}
#----------------------------------------------------------------
## Create a new argument without the dot
ENV PHP_INSTALLED_VERSION=${TARGET_PHP_VERSION//./}
#----------------------------------------------------------------
## Install necessary system dependencies, including ca-certificates for HTTPS
RUN apk update && \
    apk add --no-cache \
            bash \
            make \
            vim  \
            ca-certificates  \
            php${PHP_INSTALLED_VERSION} \
            php${PHP_INSTALLED_VERSION}-cli \
            php${PHP_INSTALLED_VERSION}-session \
            php${PHP_INSTALLED_VERSION}-mbstring \
            php${PHP_INSTALLED_VERSION}-pdo \
            php${PHP_INSTALLED_VERSION}-pdo_mysql \
            php${PHP_INSTALLED_VERSION}-tokenizer \
            php${PHP_INSTALLED_VERSION}-xml \
            php${PHP_INSTALLED_VERSION}-ctype \
            php${PHP_INSTALLED_VERSION}-json \
            php${PHP_INSTALLED_VERSION}-openssl \
            php${PHP_INSTALLED_VERSION}-fileinfo \
            php${PHP_INSTALLED_VERSION}-bcmath \
            php${PHP_INSTALLED_VERSION}-curl \
            php${PHP_INSTALLED_VERSION}-zip \
            php${PHP_INSTALLED_VERSION}-sodium \
            php${PHP_INSTALLED_VERSION}-iconv \
            php${PHP_INSTALLED_VERSION}-intl \
            php${PHP_INSTALLED_VERSION}-phar \
            php${PHP_INSTALLED_VERSION}-dom \
            php${PHP_INSTALLED_VERSION}-xmlwriter \
            libxml2-dev;
#----------------------------------------------------------------
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

