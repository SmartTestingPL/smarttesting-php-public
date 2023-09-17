FROM php:8.2.10
RUN apt-get update -yqq
RUN apt-get install -y libzip-dev librabbitmq-dev libpq-dev zip wget git procps
RUN pecl install amqp
RUN docker-php-ext-install zip sockets pdo pdo_pgsql
RUN docker-php-ext-enable amqp
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN symfony server:ca:install
