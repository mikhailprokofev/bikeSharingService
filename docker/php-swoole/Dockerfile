FROM phpswoole/swoole
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/src/php
#FROM phpswoole/swoole:latest-php:8.1-alpine

#CMD ["sudo", "chmod", "-R", "ugo+x", "/usr/src/php"]
RUN #ln -s /usr/lib /usr/local/lib64 \
    docker-php-ext-install  \
    pgsql  \
    pdo_pgsql \
    amqp \
    sockets

#ADD ./php/php.ini /usr/local/etc/php/php.ini

ENTRYPOINT ["docker-php-entrypoint"]

ARG GID
ARG UID

WORKDIR /src
USER $GID:$UID
#
##CMD ["php", "./server.php"]
