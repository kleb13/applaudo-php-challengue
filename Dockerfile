FROM php:7.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
WORKDIR /var/www/html