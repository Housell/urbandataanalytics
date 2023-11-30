FROM php:7.2-cli
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp