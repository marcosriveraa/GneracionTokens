# Usa la imagen base (ajústala a la que estés usando)
FROM php:8.1-apache

# Instalar las dependencias necesarias para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Instalar las extensiones de PHP necesarias (PDO, PDO_PGSQL, pgsql)
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Crea la carpeta 'html' con permisos específicos (por ejemplo 755)
RUN mkdir -p /var/www/html && \
    chmod 755 /var/www/html && \
    chown -R www-data:www-data /var/www/html

# Copia tu contenido a la carpeta
COPY ./html /var/www/html

# Exponer puerto
EXPOSE 80
