FROM mysql:8.0

ARG MYSQL_ROOT_PASSWORD
ARG MYSQL_DATABASE
ARG MYSQL_USER
ARG MYSQL_PASSWORD
ARG MYSQL_CLIENT_USER
ARG MYSQL_CLIENT_PASSWORD

# Set the MySQL character set and collation for better compatibility
ENV LANG=C.UTF-8 \
    MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD} \
    MYSQL_DATABASE=${MYSQL_DATABASE} \
    MYSQL_USER=${MYSQL_USER} \
    MYSQL_PASSWORD=${MYSQL_PASSWORD} \
    MYSQL_CLIENT_USER=${MYSQL_CLIENT_USER} \
    MYSQL_CLIENT_PASSWORD=${MYSQL_CLIENT_PASSWORD}

# Create a custom configuration directory inside the container
#RUN mkdir -p /etc/mysql/conf.d

# Copy a custom my.cnf configuration file to override default settings
#COPY ./.docker/mysql/my.cnf /etc/mysql/conf.d/my.cnf

# Copy the init script to the MySQL entrypoint directory (executed on first run)
#COPY ./.docker/mysql/init.sql /docker-entrypoint-initdb.d/init.sql

# Change the ownership of the configuration and script directory for security
#RUN chown -R mysql:mysql /etc/mysql/conf.d /docker-entrypoint-initdb.d


# Expose MySQL default port
EXPOSE 3306

# Use the official MySQL entrypoint script to initialize MySQL
ENTRYPOINT ["docker-entrypoint.sh"]

# Default command to start MySQL
CMD ["mysqld"]