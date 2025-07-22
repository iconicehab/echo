FROM php:8.2-cli
WORKDIR /var/www
COPY public/test.html /var/www/test.html
EXPOSE 8000
CMD php -S 0.0.0.0:${PORT:-8000} 