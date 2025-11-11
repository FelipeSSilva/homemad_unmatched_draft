# Usa imagem base com PHP e Apache
FROM php:8.2-apache

# Habilita mod_rewrite (para rotas amigáveis)
RUN a2enmod rewrite

# Permite que o Apache respeite .htaccess no diretório da aplicação
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/allow-htaccess.conf \
    && a2enconf allow-htaccess

# Instala dependências necessárias
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install zip

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia o projeto
COPY . /var/www/html

# Copia o entrypoint.sh para a raiz do container
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Cria diretório de storage (se não existir) e ajusta permissões
RUN mkdir -p /var/www/html/drafts \
    && chown -R www-data:www-data /var/www/html/drafts \
    && chmod -R 775 /var/www/html/drafts

# Instala o Composer e as dependências
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# Ajusta permissões (importante para gravação dos JSONs)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Expõe a porta padrão
EXPOSE 80

# Define o entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Comando padrão
CMD ["apache2-foreground"]
