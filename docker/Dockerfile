FROM php:8.4-apache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# update base packages
RUN apt-get update && apt-get upgrade -y \
    && apt-get install nano git wget unzip -y

# Linux Library
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev

RUN apt-get install libpq-dev libzip-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev -y \
    && docker-php-ext-install bcmath pdo_pgsql pdo_mysql mysqli pgsql exif zip \
    && docker-php-ext-enable exif \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && a2enmod rewrite

RUN chmod -R 777 /var/www/html \
    && rm /etc/localtime \
    && ln -s /usr/share/zoneinfo/America/Bahia /etc/localtime \
    && echo "America/Bahia" > /etc/timezone \
    && sed "s/;date.timezone =/date.timezone = America\/Bahia/g" /usr/local/etc/php/php.ini-development > /usr/local/etc/php/php.ini


# install node and npm
RUN apt-get install nodejs npm -y \
    && npm install -g n \
    && n lts && n latest && n prune \
    && npm install -g npm@latest

# PHP Extension
RUN docker-php-ext-install gettext intl pdo_mysql gd

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd


    # activate webserver
WORKDIR /etc/apache2/sites-available
COPY web.conf .
RUN a2ensite web.conf

# setup workdir
WORKDIR /var/www/html/convenia
RUN git config --global --add safe.directory /var/www/html/convenia

EXPOSE 80 9000
