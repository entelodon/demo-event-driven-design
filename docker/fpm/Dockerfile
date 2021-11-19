FROM php:8-fpm

ARG UID=1000
ARG GID=1000
ARG GIT_EMAIL=${GIT_EMAIL}
ARG GIT_NAME=${GIT_NAME}
ARG XDEBUG_HOST=${XDEBUG_HOST}
ARG XDEBUG_PORT=${XDEBUG_PORT}

RUN apt-get -y update && \
    apt-get -y install apt-utils

RUN DEBIAN_FRONTEND=noninteractive apt-get -y install git wget

# Install PHP Intl
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install libicu-dev && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl

# Install PHP Accelarator
RUN docker-php-ext-install opcache && \
    docker-php-ext-enable opcache

# Install PHP Zip
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y \
        libzip-dev \
        zip \
    && docker-php-ext-install zip

# Install PHP PDO
RUN docker-php-ext-install pdo_mysql

# Install xdebug
RUN pecl install xdebug-3.1.1 \
    && docker-php-ext-enable xdebug;
RUN echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.client_host='$XDEBUG_HOST >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.client_port='$XDEBUG_PORT >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.log_level=0' >> /usr/local/etc/php/conf.d/xdebug.ini

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create Internal User.
RUN groupadd -g $GID appuser && \
    useradd -m -d /home/appuser -r -u $UID -g appuser appuser

RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony/bin/symfony /usr/local/bin/symfony

USER appuser

COPY ssh_key /home/appuser/.ssh
COPY php-overrides.ini /usr/local/etc/php/conf.d/php-overrides.ini
#RUN ssh-keyscan github.com > /home/appuser/.ssh/known_hosts

RUN git config --global user.email $GIT_EMAIL
RUN git config --global user.name $GIT_NAME

WORKDIR /project/src/codebase
