FROM php:8.2-fpm as symfony_php

ENV USERNAME=www-data
ENV APP_HOME /var/www/html
ENV DB_RUN_MIGRATIONS disabled

ENV fpm_conf /usr/local/etc/php-fpm.d/www.conf
ENV php_vars /usr/local/etc/php/conf.d/docker-vars.ini


RUN apt-get update && apt-get install -yqq --no-install-recommends  libfcgi-bin libtool zip unzip libzip-dev git libpq-dev libicu-dev libpng-dev zlib1g-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_pgsql pgsql intl zip gd opcache  \
    && pecl install ast pcov redis apcu \
    && docker-php-ext-enable ast pcov redis apcu \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer
RUN echo "max_execution_time=180" > ${php_vars} &&\
    echo "max_input_time=180" >> ${php_vars} && \
    echo "error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED" >> ${php_vars}

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

USER 1000

CMD ["/bin/bash", "-c", "php-fpm -F"]

ENTRYPOINT ["docker-entrypoint"]

