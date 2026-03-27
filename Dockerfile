FROM php:8.2-cli-alpine

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

COPY . .

RUN mkdir -p storage/logs storage/cache storage/uploads storage/sessions \
    && chmod -R 775 storage

ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=http://localhost

EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public"]
