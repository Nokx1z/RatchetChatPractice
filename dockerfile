FROM php:8.2-cli

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Instalar extensión sockets (necesaria para Ratchet)
RUN docker-php-ext-install sockets

# Directorio de trabajo
WORKDIR /app

# Exponer puerto
EXPOSE 8080

# Comando por defecto (shell interactivo)
CMD ["/bin/bash"]