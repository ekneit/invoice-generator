FROM php:8.2-apache

# 1. Enable needed PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# 2. Enable Apache rewrite
RUN a2enmod rewrite

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copy project files
COPY . /var/www/html/

# 5. Set working directory
WORKDIR /var/www/html/

# 6. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# 7. Set proper permissions
RUN chown -R www-data:www-data /var/www/html
