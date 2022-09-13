FROM php:8.0-apache
WORKDIR /var/www/html
COPY . /var/www/html/
RUN docker-php-ext-install pdo_mysql 
RUN docker-php-ext-install session
#RUN docker-php-ext-install simplexml
#RUN docker-php-ext-install xmlrpc
#RUN docker-php-ext-install xmlwriter 
#RUN docker-php-ext-install xml
RUN docker-php-ext-install mysqli
EXPOSE 80