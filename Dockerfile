FROM php:8.2-apache
RUN a2enmod rewrite
COPY . /var/www/html/
RUN echo '<Directory /var/www/html>\n\
    Options -Indexes\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/app.conf \
 && a2enconf app
EXPOSE 80
