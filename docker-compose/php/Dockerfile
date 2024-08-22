# Берем за основу
FROM php:8.3-fpm

# Устанавливаем рабочую директорию
WORKDIR /var/www

ARG localUid
ARG localGid
ARG localUser

# Устанавливаем доп. зависимости и библиотеки php
RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      libpng-dev \
      libwebp-dev \
      libjpeg-dev \
      libfreetype6-dev \
      libzip-dev \
      zip unzip \
      libgd-dev \
      libgmp-dev \
      git && \
      docker-php-ext-install pdo_mysql && \
      docker-php-ext-install bcmath && \
      docker-php-ext-install gd && \
      docker-php-ext-install zip && \
      docker-php-ext-install exif && \
      docker-php-ext-install gmp && \
      docker-php-ext-install fileinfo && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Настраиваем расширения для корректной работы изображений
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install -j$(nproc) gd

# Устанавливаем phpredis
RUN pecl install redis && docker-php-ext-enable redis

# Очищаем кэш
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Копируем настройки php из локального файла в файл на ВМ
COPY ./docker-compose/php/php.ini /usr/local/etc/php/conf.d/php.ini

# Устанавливаем композер
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем юзеров, создаем группу и добавляем туда своего пользователя
USER root

RUN groupadd --gid $localGid $localUser
RUN useradd --gid $localGid --uid $localUid -m $localUser

USER $localUser
