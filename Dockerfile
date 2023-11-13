# Stage 1: Install Composer dependencies
FROM composer:2.6.5 AS composer
WORKDIR /app
COPY composer.lock composer.json /app/
RUN composer update --ignore-platform-reqs --no-scripts

# Stage 2: Final image
FROM php:8.2-fpm

# Copy composer dependencies from the composer stage

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libwebp-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install gd opcache

# Add user for the Laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
#COPY . /var/www/html

# Copy opcache config
COPY ./php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html

# Copy composer dependencies from the composer stage
COPY --chown=www:www --from=composer /app/vendor /var/www/html/vendor

# Change the current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

