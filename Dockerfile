FROM php:8.4-fpm-alpine

ARG UID=1000
ARG GID=1000

RUN apk add --no-cache \
    git \
    unzip \
    postgresql-dev \
  && addgroup -S -g ${GID} appgroup \
  && adduser  -S -u ${UID} -G appgroup -h /home/appuser appuser \
  && install -d -o appuser -g appgroup /home/appuser/.composer /var/www \
  && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_HOME=/home/appuser/.composer
ENV PATH="${COMPOSER_HOME}/vendor/bin:${PATH}"

COPY .docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/custom-app.conf

WORKDIR /var/www
USER appuser

EXPOSE 9000
