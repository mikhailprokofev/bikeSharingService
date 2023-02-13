FROM php:8.1-fpm-alpine
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions zip \
    pgsql \
    pdo_pgsql \
    amqp \
    sockets \
    bcmath

#ADD ./php.ini /usr/local/etc/amphp/php.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && mv composer.phar /usr/bin/composer

ARG GID
ARG UID

WORKDIR /var/www
USER $GID:$UID

CMD ["php-fpm"]
