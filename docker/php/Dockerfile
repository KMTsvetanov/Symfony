FROM php:8.2.11-fpm-alpine as app

# Set the umask to allow 777 permissions for all new files
RUN echo "umask 000" >> /etc/profile


# Useful PHP extension installer image, copy binary into your container
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install php extensions
# exit on errors, exit on unset variables, print every command as it is executed
RUN set -eux; \
    install-php-extensions \
        pdo \
        pdo_mysql \
        intl \
        zip \
        apcu \
        opcache

RUN apk add --no-cache \
        bash \
        acl \
        fcgi \
        file \
        gettext \
        git \
        nano

# allow super user - set this if you use Composer as a
# super user at all times like in docker containers
ENV COMPOSER_ALLOW_SUPERUSER=1


# obtain composer using multi-stage build
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Node.js and yarn packages
RUN apk add --no-cache \
    nodejs \
    yarn

WORKDIR /var/www

RUN rm -rf ./html

#Here, we are copying only composer.json and composer.lock (instead of copying the entire source)
# right before doing composer install.
# This is enough to take advantage of docker cache and composer install will
# be executed only when composer.json or composer.lock have indeed changed!-
COPY ./app/composer.* ./

# install
RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

# copy application files to the working directory
COPY ./app .

# Set permissions to allow Symfony commands to run without permission issues
RUN chown -R www-data:www-data .
RUN chmod -R 775 ./var

# run composer dump-autoload --optimize
RUN composer dump-autoload --optimize

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

FROM app as app_dev

# Xdebug has different modes / functionalities. We can default to 'off' and set to 'debug'
# when we run docker compose up if we need it
ENV XDEBUG_MODE=off

COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini

# Install xdebug
RUN set -eux; \
	install-php-extensions xdebug-3.2.2