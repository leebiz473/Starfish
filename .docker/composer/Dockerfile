FROM composer:latest

# Set the working directory inside the container
WORKDIR /var/www/app

RUN composer create-project --prefer-dist laravel/laravel .

ENTRYPOINT [ "composer", "--ignore-platform-reqs" ]