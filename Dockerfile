# Use PHP 8.4 with Apache
FROM php:8.4-apache

# 1. Install system dependencies (git, unzip, python3 for the AI agent)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    python3 \
    python3-pip \
    python3-venv \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 3. Install Python dependencies (LangChain/OpenAI if used by your script)
# Adjust these packages based on what your python script actually imports
RUN pip3 install requests pandas --break-system-packages

# 4. Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Set working directory
WORKDIR /var/www/html

# 7. Copy application files
COPY . .

# 8. Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 9. Set permissions for Laravel storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Update Apache config to point to public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 11. Expose the web port
EXPOSE 80