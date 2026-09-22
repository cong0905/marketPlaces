FROM php:8.2-fpm

# Cài đặt các dependencies cơ bản và thư viện cần thiết
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    nodejs \
    npm \
    ca-certificates

# Xoá cache apt để giảm kích thước image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài đặt các PHP extensions cần thiết cho Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www

# Copy file cấu hình Nginx
COPY deploy/nginx.conf /etc/nginx/sites-enabled/default

# Copy mã nguồn dự án vào container
COPY . /var/www

# Cấp quyền cho thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Cài đặt PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Cài đặt Node dependencies và build Vite assets
RUN npm install && npm run build

# Copy script khởi động
COPY deploy/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose port (Render sẽ tự động cung cấp biến môi trường $PORT, mặc định là 10000 hoặc 80)
EXPOSE 80

# Chạy start script
CMD ["/usr/local/bin/start.sh"]
