FROM php:7.2-apache

RUN apt-get update && apt-get install software-properties-common && apt-get update && apt-get upgrade && apt-get install -y \
        php7.2-mbstring \
        php7.2-soap \
        php7.2-zip \
        php7.2-mysql \
        php7.2-curl \
        php7.2-gd \
        php7.2-xml \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        zip \
        curl \
        unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-source delete

COPY docker.conf /etc/apache2/sites-available/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite