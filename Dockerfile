FROM php:8.2-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    apt-utils \
    zsh \
    htop \
    iptables \
    libc6 \
    procps \
    nano \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    zip \
    telnet \
    traceroute \
    lsof \
    unzip \
    git \
    net-tools \
    iputils-ping \
    sqlite3 \
    libsqlite3-dev \
    libmagickwand-dev --no-install-recommends \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка расширений PHP
RUN docker-php-ext-install pdo mbstring pdo_sqlite pdo_mysql


# Установка Imagick через PECL
RUN apt-get update && apt-get install -y libmagickwand-dev && \
    pecl install imagick && \
    docker-php-ext-enable imagick

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка Node.js
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Установка GD с поддержкой JPEG и FreeType
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Установка прав для директорий
WORKDIR /var/www
COPY . .

RUN composer require symfony/filesystem --no-interaction --no-scripts

# Установка зависимостей проекта
RUN composer install
RUN npm install
RUN npm run build

# Изменение владельца и прав на папку
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Настройка PHP-FPM для прослушивания на всех интерфейсах
RUN sed -i 's|listen = .*|listen = 0.0.0.0:9000|' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's|;listen.owner =.*|listen.owner = www-data|' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's|;listen.group =.*|listen.group = www-data|' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's|;listen.mode =.*|listen.mode = 0660|' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's|;request_terminate_timeout =.*|request_terminate_timeout = 600s|' /usr/local/etc/php-fpm.d/www.conf

# Изменение параметров для размера загрузки и времени выполнения в php.ini
RUN sed -i 's/upload_max_filesize = .*/upload_max_filesize = 100M/' /usr/local/etc/php/php.ini-production && \
    sed -i 's/post_max_size = .*/post_max_size = 100M/' /usr/local/etc/php/php.ini-production && \
    sed -i 's/max_execution_time = .*/max_execution_time = 600/' /usr/local/etc/php/php.ini-production

USER 33

# Открытие порта
EXPOSE 9000

# Запуск PHP-FPM
CMD ["php-fpm", "-F"]

