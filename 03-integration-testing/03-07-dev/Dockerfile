FROM php:8.2.10
# install extensions
RUN apt-get update -yqq
RUN apt-get install -y libzip-dev librabbitmq-dev libpq-dev zip wget git
RUN docker-php-ext-install pdo pdo_pgsql
RUN pecl install amqp
RUN docker-php-ext-enable amqp
RUN rm -rf /var/cache/apk/*

# install composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# install symfony cli
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# set memory limit
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

# hide X-Powered-By in reponse header
RUN echo "expose_php=off" > /usr/local/etc/php/conf.d/expose.ini

RUN mkdir /app
WORKDIR /app

COPY . .

ENV APP_ENV=prod
RUN composer install
