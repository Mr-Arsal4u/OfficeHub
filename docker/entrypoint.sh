#!/usr/bin/env bash
set -e

cd /var/www/html

# If no .env but .env.example exists, copy it
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

# Ensure storage directories exist & are writable
mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Install deps if vendor missing (first run)
if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Generate app key if missing
php artisan key:generate --force || true

# Clear caches (avoid stale config causing 419)
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Wait for DB to be ready (compose healthcheck already helps; this is extra safety)
echo "Waiting for database..."
ATTEMPTS=0
until php -r '
$h="db"; $P=3306; $u="laravel"; $p="secret"; $d="office_hub";
try { new PDO("mysql:host=$h;port=$P;dbname=$d",$u,$p,[PDO::ATTR_TIMEOUT=>2]); exit(0); }
catch (Exception $e) { exit(1); }'; do
  ATTEMPTS=$((ATTEMPTS+1))
  if [ $ATTEMPTS -gt 30 ]; then
    echo "Database is not ready after waiting. Continuing..."
    break
  fi
  sleep 2
done

# Run migrations automatically (dev/demo convenience)
php artisan migrate --force || true

# Hand off to Apache
exec "$@"
