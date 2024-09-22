FROM php:8.1-apache

# Cài đặt phần mở rộng mysqli
RUN docker-php-ext-install mysqli

# Bật mod_rewrite cho Apache (nếu cần)
RUN a2enmod rewrite