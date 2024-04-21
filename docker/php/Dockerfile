FROM php:8.1-fpm

# Set working directory
WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    wget \
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
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Symfony CLI installation and setup
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Add user for symfony application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Change current user to www
USER www

# Copy existing application directory contents
COPY --chown=www:www app/ /app/

## Change current user
USER root
# Config PHP
RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
COPY docker/php/conf.d/ $PHP_INI_DIR/conf.d/

# Expose port 9000 and start php server
EXPOSE 8000
CMD ["php-fpm"]

WORKDIR /app