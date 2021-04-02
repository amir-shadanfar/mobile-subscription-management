FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    supervisor \
    cron \
    git \
    unzip \
    pkg-config \
    zlib1g-dev \
    libpng-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libicu-dev \
    g++ \
    wkhtmltopdf \
    libjpeg62-turbo-dev \
    libfreetype6-dev

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/UTC /etc/localtime
RUN "date"


# Type docker-php-ext-install to see available extensions
#RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install pdo pdo_mysql opcache sockets gd intl exif
#zip

ADD cron /etc/cron.d/api-cron
RUN chmod 0644 /etc/cron.d/api-cron
RUN crontab /etc/cron.d/api-cron

ADD ./supervisord.conf /etc/supervisord.conf
ADD ./entrypoint.sh /var/local/entrypoint.sh

RUN chmod +x /var/local/entrypoint.sh

# Create symlink for wkhtmltopdf
RUN ln -s /usr/bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf

WORKDIR /www
