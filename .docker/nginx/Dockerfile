FROM nginx:latest

## Copy build scripts and ensure build scripts are executable
COPY ./.docker/.shared/ /tmp/scripts/
RUN chmod -R 777 /tmp/scripts/
RUN chmod +x /tmp/scripts/

# set timezone
ARG TZ=GMT
RUN /tmp/scripts/set-timezone.sh ${TZ}

## add users
ARG APP_USER=www-data
ARG APP_GROUP=www-data
ARG APP_USER_ID=1000
ARG APP_GROUP_ID=1000

RUN /tmp/scripts/create-user.sh ${APP_USER} ${APP_GROUP} ${APP_USER_ID} ${APP_GROUP_ID}

RUN /tmp/scripts/install-deps.sh

## Copy Nginx configuration and replace directives with variables using script
COPY .docker/nginx/conf.d/nginx.conf /etc/nginx/nginx.conf
ARG APP_USER
RUN /tmp/scripts/modify-config.sh /etc/nginx/nginx.conf \
    "__APP_USER" \
    "${APP_USER}" \
 && /tmp/scripts/modify-config.sh /etc/nginx/nginx.conf \
    "__APP_GROUP" \
    "${APP_GROUP}" \
;

## Copy Nginx App configuration and replace directives with variables using script
COPY .docker/nginx/conf.d/sites-available/default.conf /etc/nginx/sites-available/default.conf
ARG APP_CODE_PATH
RUN /tmp/scripts/modify-config.sh /etc/nginx/sites-available/default.conf \
    "__NGINX_ROOT" \
    "${APP_CODE_PATH}" \
;

## Default domain (optional fallback if not provided in the .env file)
ARG DOMAIN_NAME=default.envx

## Add http-certificate.sh, ensure that it is excuteable and run
COPY .docker/nginx/http-certificate.sh /usr/local/bin/http-certificate.sh

## Make the script executable
RUN chmod +x /usr/local/bin/http-certificate.sh
RUN /usr/local/bin/http-certificate.sh

## Set working directory
WORKDIR /etc/nginx/

## Cleanup build
RUN /tmp/scripts/cleanup.sh

## Expose port 80 and 443 for nginx
EXPOSE 80 443

## Ensure script runs before Nginx starts
#CMD ["/bin/bash", "-c", "/usr/local/bin/http-certificate.sh && /docker-entrypoint.sh nginx -g 'daemon off;'"]

CMD ["nginx"]