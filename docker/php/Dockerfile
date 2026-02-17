FROM php:8.4-fpm

ARG USER_ID=1000
ARG GROUP_ID=1000

# Устанавливаем системные зависимости + curl для установки Node
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    zip \
    unzip \
    git \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Устанавливаем Node.js (версия 20)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Часовой пояс
ENV TZ=Europe/Minsk
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Пользователь
RUN groupadd -g ${GROUP_ID} dev && \
    useradd -u ${USER_ID} -g dev -m -s /bin/bash dev

WORKDIR /var/www/html
USER dev

CMD ["php-fpm"]