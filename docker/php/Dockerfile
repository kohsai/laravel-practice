FROM php:8.1-fpm

# 必要なパッケージとPHP拡張をインストール
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

# Composerのインストール（グローバル）
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# 作業ディレクトリの指定（任意）
WORKDIR /var/www/html
