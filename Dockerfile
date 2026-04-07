FROM php:8.2-cli-alpine

WORKDIR /var/www/html

RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

COPY . .

RUN chmod +x /var/www/html/docker-entrypoint.sh

RUN mkdir -p storage/logs storage/cache storage/uploads storage/sessions \
    && chmod -R 775 storage

ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=http://localhost

EXPOSE 8080

CMD ["/var/www/html/docker-entrypoint.sh"]
