# Complete Guide to Hosting Sylius

Follow these exact steps to host your Sylius v2 project (with PrinterTheme) on your server with a clean database and zero asset/CSS errors.

## 0. Prerequisites
Ensure your server has the following installed:
- **PHP 8.2+** with extensions: `intl`, `pdo_mysql`, `gd`, `zip`, `xml`, `mbstring`, `curl`, `opcache`.
- **MySQL 8.0+** (or MariaDB equivalent).
- **Composer 2**.
- **Node.js 18+** & **Yarn** (for compiling CSS/JS).
- **Nginx** or **Apache** (Nginx is highly recommended for Sylius).

---

## 1. Prepare your environment
Upload your project files to your server (e.g., via SFTP or Git) to your web directory, usually `/var/www/sylius`.

1. Go to your project directory:
   ```bash
   cd /var/www/sylius
   ```

2. Copy the `.env` file to `.env.local` and configure your database and environment settings:
   ```bash
   cp .env .env.local
   nano .env.local
   ```
   **Crucial Variables to set:**
   ```env
   APP_ENV=prod
   APP_DEBUG=0
   DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0&charset=utf8mb4"
   ```

---

## 2. Install Dependencies
Run composer to install PHP dependencies optimized for production.
```bash
composer install --no-dev --optimize-autoloader
```

---

## 3. Fresh Database Installation
Since you requested a *clean DB*, we will drop any existing database, create a fresh one, run migrations, and install the base Sylius fixtures (like admin user and basic store configurations).

Run the following commands:
```bash
php bin/console doctrine:database:drop --force --if-exists --env=prod
php bin/console doctrine:database:create --env=prod
php bin/console doctrine:migrations:migrate -n --env=prod
```

Next, to initialize the shop with clean default data (channels, currencies, admin account, but no sample products so it stays clean):
```bash
php bin/console sylius:install:database -n --env=prod
php bin/console sylius:install:setup -n --env=prod
```
*Note: During `setup`, it will prompt you to create an admin account (email & password).*

---

## 4. Build Assets (HTML/CSS/JS without errors)
To make your theme and styles load perfectly without any missing CSS or JS, build the assets cleanly.

1. Install JS dependencies:
   ```bash
   yarn install
   ```

2. Build for production (This compiles `PrinterTheme` and Sylius assets into `public/build`):
   ```bash
   yarn encore production
   ```

3. Install Sylius legacy assets (just in case they are needed for some plugins/admin panel):
   ```bash
   php bin/console sylius:install:assets --env=prod
   ```

---

## 5. Clear Cache & Warmup
Ensure the new environment is fully cached for `prod` so there are no lingering HTML/CSS path issues.
```bash
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
```

---

## 6. Set Proper File Permissions
This is usually the #1 reason why images or CSS fail to load, or caches throw 500 errors. Allow the web server (e.g., `www-data` on Ubuntu) to read/write specific folders.

```bash
# Change owner to your web user (usually www-data)
sudo chown -R www-data:www-data /var/www/sylius

# Give write permissions to var/ and public/media/
chmod -R 775 var/cache var/log public/media public/build
```

---

## 7. Web Server Configuration (Nginx)
Configure your Nginx block to point to the `public/` directory explicitly. This ensures URLs resolve properly.

Create a file in `/etc/nginx/sites-available/sylius`:
```nginx
server {
    listen 80;
    server_name your_domain.com www.your_domain.com;
    root /var/www/sylius/public;

    index index.php;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        
        # Check your PHP version below! e.g., php8.2-fpm.sock
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; 
        
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable it and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/sylius /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

### Final Note for the new Logo:
To ensure the logo you provided is displayed correctly, upload your `novaprint` logo image inside your public assets folder:
Overwrite this file on the server (or locally before uploading):
`public/assets/shop/img/logo.png` (Or wherever `PrinterTheme` specifically points to it).
Clear the cache right after replacing the image!
