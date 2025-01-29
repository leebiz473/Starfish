ARG NGINX_VERSION
FROM nginx:${NGINX_VERSION} as base

## Copy build scripts and ensure build scripts are executable
COPY ./.docker/.shared/ /tmp/scripts/
RUN chmod -R 777 /tmp/scripts/
RUN chmod +x /tmp/scripts/

## set timezone
ARG TZ=GMT
RUN /tmp/scripts/set-timezone.sh ${TZ}

## Handle nginx configuration
COPY .docker/nginx/conf.d/nginx.conf /etc/nginx/nginx.conf
COPY .docker/nginx/conf.d/sites-available/default.conf /etc/nginx/conf.d/default.conf

##
RUN apk add openssl

## Default domain (optional fallback if not provided in the .env file)
ARG DOMAIN_NAME=default.envx

RUN echo "Check 1: /usr/local/bin/"
RUN ls /usr/local/bin/

## Add http-certificate.sh, ensure that it is excuteable and run
COPY ./.docker/nginx/http-certificate.sh /usr/local/bin/http-certificate.sh

RUN echo "Check 2: /usr/local/bin/"
RUN ls /usr/local/bin/

## Make the script executable
RUN chmod +x /usr/local/bin/http-certificate.sh
RUN /usr/local/bin/http-certificate.sh

## Cleanup build
RUN /tmp/scripts/cleanup.sh && rm -rf /usr/local/bin/http-certificate.sh

## Set working directory
WORKDIR /etc/nginx/

## Expose port 80 and 443 for nginx
EXPOSE 80 443

CMD ["nginx"]