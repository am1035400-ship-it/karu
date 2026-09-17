FROM php:8.2-apache

# Update and install system dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by CodeIgniter 4 and the panel
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli pdo pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Change Apache document root to public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy application files
COPY . /var/www/html

# Set proper permissions for CodeIgniter's writable directory
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable \
    && chmod -R 775 /var/www/html/public

# Expose standard port 80 (Render forwards to this)
EXPOSE 80
