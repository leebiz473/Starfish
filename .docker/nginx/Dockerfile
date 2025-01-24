FROM nginx:latest

# Default domain (optional fallback if not provided in the .env file)
ENV DOMAIN_NAME=default.envx

# Copy Nginx configuration
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Add http-certificate.sh, ensure that it is excuteable and run
COPY .docker/nginx/http-certificate.sh /usr/local/bin/http-certificate.sh

# Make the script executable
RUN chmod +x /usr/local/bin/http-certificate.sh
#RUN /usr/local/bin/http-certificate.sh

# Expose port 80 and 443 for nginx
EXPOSE 80 443

# Ensure script runs before Nginx starts
CMD ["/bin/bash", "-c", "/usr/local/bin/http-certificate.sh && /docker-entrypoint.sh nginx -g 'daemon off;'"]