# ============================================================
# PARADOX SYSTEMS — Dockerfile
# Deployment target: Render.com
# Base: php:8.2-apache (Apache + PHP built-in)
# ============================================================

FROM php:8.2-apache

# Enable Apache mod_rewrite (needed for clean URLs if added later)
RUN a2enmod rewrite
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && rm -rf /var/lib/apt/lists/*

# Set Apache to read the PORT env variable that Render injects at runtime.
# Render routes external traffic → container's $PORT (default 10000).
# We write a startup script that patches Apache's port before launch.
RUN echo '#!/bin/bash\n\
PORT=${PORT:-80}\n\
sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf\n\
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-enabled/000-default.conf\n\
exec apache2-foreground' > /usr/local/bin/start.sh \
  && chmod +x /usr/local/bin/start.sh

# Copy ALL site files into Apache's web root
COPY . /var/www/html/

# Fix permissions so Apache (www-data) can read everything
RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 755 /var/www/html \
  && chmod 644 /var/www/html/data/contacts.txt

# Remove the default Apache index.html so our index.html takes over
RUN rm -f /var/www/html/index.html.default 2>/dev/null || true

# Expose port 80 (Render overrides this via $PORT at runtime)
EXPOSE 80

# Start Apache via our wrapper script so $PORT is applied
CMD ["/usr/local/bin/start.sh"]
