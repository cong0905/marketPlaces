#!/bin/sh

# Cập nhật cổng Nginx để sử dụng biến môi trường $PORT của Render
sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/sites-enabled/default
sed -i "s/listen \[::\]:80;/listen [::]:${PORT:-80};/g" /etc/nginx/sites-enabled/default

# Xóa cache Laravel
php artisan optimize:clear

# Tạo symlink cho thư mục chứa ảnh (Sửa lỗi ảnh 404)
php artisan storage:link

# Chạy migrations (Bắt buộc phải có cờ --force trong môi trường production)
php artisan migrate --force

# Khởi động PHP-FPM trong background
php-fpm &

# Khởi động Laravel Reverb (chạy ngầm)
php artisan reverb:start --host=0.0.0.0 --port=8080 &

# Khởi động Nginx ở foreground
nginx -g "daemon off;"
