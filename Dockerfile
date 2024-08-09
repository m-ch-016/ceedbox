# Base image
FROM php:8.2.12-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    python3 \
    make \
    git \
    icu-dev \
    libsodium-dev \
    libzip-dev \
    libpng-dev \
    chromium \
    sudo \
    nss \
    freetype \
    harfbuzz \
    ca-certificates \
    ttf-freefont \
    curl \
    unzip \
    msttcorefonts-installer \
    && update-ms-fonts \
    && docker-php-ext-install zip \
    && echo "System dependencies installed"

# Install Microsoft ODBC drivers and tools
RUN curl -O https://download.microsoft.com/download/8/6/8/868e5fc4-7bfe-494d-8f9d-115cbcdb52ae/msodbcsql18_18.1.2.1-1_amd64.apk \
    && curl -O https://download.microsoft.com/download/8/6/8/868e5fc4-7bfe-494d-8f9d-115cbcdb52ae/mssql-tools18_18.1.1.1-1_amd64.apk \
    && apk add --allow-untrusted msodbcsql18_18.1.2.1-1_amd64.apk \
    && apk add --allow-untrusted mssql-tools18_18.1.1.1-1_amd64.apk \
    && echo "ODBC drivers and tools installed"

# Install wkhtmltopdf
COPY --from=surnet/alpine-wkhtmltopdf:3.18.0-0.12.6-full /bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf-amd64
COPY --from=surnet/alpine-wkhtmltopdf:3.18.0-0.12.6-full /bin/wkhtmltoimage /usr/local/bin/wkhtmltoimage-amd64
RUN echo "wkhtmltopdf installed"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && echo "Composer installed"

# Increase PHP memory limit and file size limits
RUN echo 'memory_limit = 2048M' > /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
    && echo 'upload_max_filesize = 100M' > /usr/local/etc/php/conf.d/docker-php-max-filesize.ini \
    && echo 'post_max_size = 25M' > /usr/local/etc/php/conf.d/docker-php-post-max-size.ini \
    && echo "PHP settings updated"


# Update PHP-FPM listen address
RUN sed -i 's/listen = 127.0.0.1:9000/listen = 9000/' /usr/local/etc/php-fpm.d/www.conf

# Install Nginx
COPY --from=nginxinc/nginx-unprivileged:alpine-slim / /
COPY ./webserver/default.conf /etc/nginx/conf.d/default.conf
RUN adduser --disabled-password -G www-data www-data \
    && echo "Nginx installed and configured"


COPY ./webserver/default.conf /etc/nginx/conf.d/default.conf

# Assets stage
FROM node:19-alpine AS assets
WORKDIR /app
COPY . .
RUN npm install && npm run build \
    && echo "Assets built"

# Production build
FROM base AS production
WORKDIR /var/www/html
COPY --from=assets --chown=www-data:www-data /app/ .
RUN composer install --ignore-platform-reqs --no-interaction \
    && php artisan optimize:clear \
    && echo "Composer dependencies installed and Laravel optimized"
USER www-data
CMD ["sh", "-c", "/usr/local/sbin/php-fpm -D && nginx -g 'daemon off;'"]
