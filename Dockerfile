# BukaKios WebView Simple - Docker Image
# PHP-FPM + Nginx dalam satu container

FROM php:8.1-fpm-alpine

# Install Nginx + ekstensi PHP
RUN apk add --no-cache \
    nginx \
    curl \
    && docker-php-ext-install pdo \
    && rm -rf /var/cache/apk/*

# Buat user non-root
RUN addgroup -g 1000 appgroup \
    && adduser -u 1000 -G appgroup -s /bin/sh -D appuser

# Set working directory
WORKDIR /var/www/html

# Copy konfigurasi Nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy seluruh source code
COPY . .

# Set permission (exclude .env agar tetap bisa di-mount dari host)
RUN chown -R appuser:appgroup /var/www/html \
    && chown -R appuser:appgroup /var/log/nginx \
    && chown -R appuser:appgroup /var/lib/nginx \
    && chown -R appuser:appgroup /var/run/nginx

# Expose port 80
EXPOSE 80

# Jalankan Nginx + PHP-FPM bersamaan
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"
