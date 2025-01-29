ARG ALPINE_VERSION
FROM alpine:${ALPINE_VERSION} AS php-base

# make build args available as ENV variables to downstream images
# so that we don't have to pass the same build args again
ARG APP_USER_ID
ARG APP_GROUP_ID
ARG APP_USER_NAME
ARG APP_GROUP_NAME
ARG APP_CODE_PATH
ARG TARGET_PHP_VERSION
ARG ALPINE_VERSION
ARG APP_ENV
ENV APP_USER_ID=${APP_USER_ID}
ENV APP_GROUP_ID=${APP_GROUP_ID}
ENV APP_USER_NAME=${APP_USER_NAME}
ENV APP_GROUP_NAME=${APP_GROUP_NAME}
ENV APP_CODE_PATH=${APP_CODE_PATH}
ENV TARGET_PHP_VERSION=${TARGET_PHP_VERSION}
ENV ALPINE_VERSION=${ALPINE_VERSION}

RUN addgroup -g $APP_GROUP_ID $APP_GROUP_NAME && \
    adduser -D -u $APP_USER_ID -s /bin/bash $APP_USER_NAME -G $APP_GROUP_NAME && \
    mkdir -p $APP_CODE_PATH && \
    chown $APP_USER_NAME: $APP_CODE_PATH

# Install necessary system dependencies, including ca-certificates for HTTPS
RUN apk add --no-cache \
            bash \
            make \
            vim  \
            ca-certificates  \
            php${PHP_VERSION} \
            php${PHP_VERSION}-cli \
            php${PHP_VERSION}-fpm \
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
            php${PHP_VERSION}-iconv \
            php${PHP_VERSION}-intl


# Ensure CA certificates are up-to-date
RUN update-ca-certificates

# Modifies alpines default shell to use $bash instead of $ash
RUN sed -e 's;/bin/ash$;/bin/bash;g' -i /etc/passwd

## Add PHP configuration
COPY ./.docker/php/conf.d/app.ini  /usr/local/etc/php/conf.d/
COPY ./.docker/php/conf.d/app-${APP_ENV}.ini  /usr/local/etc/php/conf.d/

# Add bash configuration
COPY ./.docker/php/.bashrc /home/${APP_USER_NAME}/.bashrc
COPY ./.docker/php/.bashrc /root/.bashrc

WORKDIR $APP_CODE_PATH


RUN apk add --no-cache --update \
        mysql-client \
        redis