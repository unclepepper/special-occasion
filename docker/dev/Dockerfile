FROM unit:php8.4

ARG UID
ARG GID

RUN usermod -u ${UID} www-data && groupmod -g ${GID} www-data

# Устанавливаем необходимые пакеты для PostgreSQL
RUN apt-get update && apt-get install -y \
    unzip \
    nano \
    git \
    zip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean

RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY docker/dev/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


COPY unit-config.json /docker-entrypoint.d/config.json
COPY unit-config.json /etc/unit/unit.json

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --version="2.8.1" --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"


COPY docker/dev/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh



WORKDIR /var/www/app



COPY . /var/www/app

ENTRYPOINT ["/entrypoint.sh"]
