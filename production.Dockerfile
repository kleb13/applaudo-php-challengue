FROM composer:1.7 as vendor

COPY src/database/ database

COPY src/composer.json composer.json
COPY src/composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-dev \
    --no-scripts \
    --prefer-dist


FROM node:8.11 as frontend

RUN mkdir -p /app/public

COPY src/package.json src/webpack.mix.js src/package-lock.json /app/
COPY src/resources/ /app/resources/

WORKDIR /app

RUN npm install
RUN npm run production


FROM php:7.2-apache-stretch
RUN sed -i -e "s/html/html\/public/g" /etc/apache2/sites-enabled/000-default.conf
RUN chown -R www-data:www-data /var/www/html
ENV PORT 9001
RUN sed -i "s/80/"'${PORT}'"/g" /etc/apache2/sites-enabled/000-default.conf /etc/apache2/ports.conf
COPY src/ /var/www/html
COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=frontend /app/public/js/ /var/www/html/public/js/
COPY --from=frontend /app/public/css/ /var/www/html/public/css/
COPY --from=frontend /app/mix-manifest.json /var/www/html/mix-manifest.json
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql
RUN chgrp -R www-data /var/www/html/storage /var/www/html/bootstrap/cache && chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache
CMD apache2-foreground

