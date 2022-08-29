FROM php:8.1-apache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update \
  && apt-get install -y vim \
  && apt-get install -y unzip \
  && apt-get install -y libicu-dev \
  && docker-php-ext-install intl \
  && docker-php-ext-install pdo_mysql \
  && a2enmod rewrite