ARG NGINX_VERSION
FROM nginx:${NGINX_VERSION} as base
#----------------------------------------------------------------
## Copy build scripts and ensure build scripts are executable
COPY ./.docker/.shared/ /tmp/scripts/
RUN chmod -R 777 /tmp/scripts/
RUN chmod +x /tmp/scripts/
#----------------------------------------------------------------
## set timezone
ARG TZ=GMT
RUN /tmp/scripts/set-timezone.sh ${TZ}
#----------------------------------------------------------------
## Handle nginx configuration
COPY .docker/nginx/conf.d/nginx.conf /etc/nginx/nginx.conf
COPY .docker/nginx/conf.d/sites-available/default.conf /etc/nginx/conf.d/default.conf
#----------------------------------------------------------------
# Add user
RUN adduser -D www-app
##----------------------------------------------------------------
##
RUN apk add openssl
#----------------------------------------------------------------
## Default domain (optional fallback if not provided in the .env file)
ARG DOMAIN_NAME=default.envx
#----------------------------------------------------------------
## Add http-certificate.sh, ensure that it is excuteable and run
COPY ./.docker/nginx/http-certificate.sh /usr/local/bin/http-certificate.sh
#----------------------------------------------------------------
## Make the script executable
RUN chmod +x /usr/local/bin/http-certificate.sh
#----------------------------------------------------------------
## Cleanup build
RUN /tmp/scripts/cleanup.sh
#----------------------------------------------------------------
## Set working directory
WORKDIR /etc/nginx/
#----------------------------------------------------------------
## Expose port 80 and 443 for nginx
EXPOSE 80 443
#----------------------------------------------------------------
## Run the cert creation script and then start Nginx
CMD ["/bin/sh", "-c", "/usr/local/bin/http-certificate.sh && nginx"]