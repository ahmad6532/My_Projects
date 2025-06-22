FROM php:8.2-fpm AS base
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

RUN apt-get install -y libmcrypt-dev \
    libpng-dev libxml2-dev \
    && docker-php-source extract \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ \
    --with-png-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd mcrypt mysqli pdo pdo_mysql zip opcache \
    && docker-php-source delete 

WORKDIR /var/www/html

COPY composer.json ./
RUN composer install --no-scripts --no-autoloader

COPY . .
RUN chmod +x artisan

RUN composer dump-autoload --optimize && composer run-script post-install-cmd

RUN php artisan serve --host 0.0.0.0 --port 5001

RUN echo "PHP Started"

RUN npm install

RUN npm run build

CMD [ "npm run preview" ]