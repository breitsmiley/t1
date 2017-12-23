FROM composer:1.5
FROM php:7.2-fpm-alpine

RUN apk add --no-cache --virtual .persistent-deps \
		git \
		icu-libs \
		libpq \
		zlib

#ENV APCU_VERSION 5.1.8
RUN set -xe \
	&& apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		postgresql-dev \
		zlib-dev \
	&& docker-php-ext-install \
		intl \
		pdo_pgsql \
		zip \
#	&& pecl install \
#		apcu-${APCU_VERSION} \
#	&& docker-php-ext-enable --ini-name 20-apcu.ini apcu \
	&& docker-php-ext-enable --ini-name 05-opcache.ini opcache \
	&& apk del .build-deps

COPY --from=0 /usr/bin/composer /usr/bin/composer

COPY /docker/php-fpm/php.ini /usr/local/etc/php/

COPY /docker/php-fpm/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# Add tool for waiting available of other service
COPY /docker/wait-for /usr/local/bin/wait-for
RUN chmod +x /usr/local/bin/wait-for

WORKDIR /srv/blog
ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative

# Prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress --no-suggest \
	&& composer clear-cache

COPY / ./

RUN mkdir -p var/cache var/logs var/sessions \
	&& composer dump-autoload --classmap-authoritative --no-dev \
	&& chown -R www-data var