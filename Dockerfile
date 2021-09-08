FROM php:5.6-alpine

RUN apk --update --no-cache add autoconf g++ make && \
    pecl install -f xdebug-2.5.5 && \
    docker-php-ext-enable xdebug && \
    apk del --purge autoconf g++ make

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /usr/src/app
