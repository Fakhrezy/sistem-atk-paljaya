# Sistem Monitoring Barang Habis Pakai (MBP)

Sistem informasi untuk monitoring dan manajemen barang habis pakai berbasis web menggunakan Laravel dan Docker.

## Persyaratan Sistem

-   [Docker Desktop](https://www.docker.com/products/docker-desktop/)
-   [Git](https://git-scm.com/downloads)
-   [Composer](https://getcomposer.org/download/) (opsional, jika ingin development di lokal)
-   [PHP 8.2](https://www.php.net/downloads.php) (opsional, jika ingin development di lokal)

## Langkah Instalasi

### 1. Clone Repository

```bash
# Clone repository
git clone https://github.com/Fakhrezy/sistem-mbp-paljaya.git

# Masuk ke direktori project
cd sistem-mbp-paljaya
```

### 2. Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Sesuaikan konfigurasi database di file .env:
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sistem-atk-paljaya
DB_USERNAME=sail
DB_PASSWORD=password

# Tambahkan konfigurasi port:
APP_PORT=8081                    # Port untuk aplikasi web
FORWARD_DB_PORT=3307            # Port untuk MySQL
FORWARD_PHPMYADMIN_PORT=8082    # Port untuk phpMyAdmin

# Sesuaikan URL aplikasi:
APP_URL=http://localhost:8081

# Tambahkan konfigurasi user:
WWWGROUP=1000
WWWUSER=1000
```

### 3. Install Dependencies

```bash
# Install composer dependencies
composer install

# Install npm dependencies & build assets (opsional)
npm install
npm run build
```

### 4. Setup Docker dan Menjalankan Aplikasi

```bash
# Start Docker Desktop
# Pastikan Docker Desktop sudah berjalan

# Jalankan container Docker
docker compose up -d

# Tunggu proses build selesai...

# Jalankan migrasi database
docker exec atk-app php artisan migrate

# Generate application key
docker exec atk-app php artisan key:generate

# Create storage link
docker exec atk-app php artisan storage:link

# Cache configuration
docker exec atk-app php artisan config:cache
docker exec atk-app php artisan route:cache
docker exec atk-app php artisan view:cache
```

## Mengakses Aplikasi

Setelah instalasi selesai, Anda dapat mengakses:

-   Aplikasi Web: [http://localhost:8081](http://localhost:8081)
-   phpMyAdmin: [http://localhost:8082](http://localhost:8082)

Login default:

-   Email: [admin@paljaya.com](mailto:admin@paljaya.com)
-   Password: password

## Perintah Docker yang Sering Digunakan

```bash
# Menjalankan container
docker compose up -d

# Menghentikan container
docker compose down

# Melihat log aplikasi
docker logs atk-app

# Menjalankan perintah artisan
docker exec atk-app php artisan <command>

# Masuk ke shell container
docker exec -it atk-app bash
```

## Database Management

Anda dapat mengelola database melalui phpMyAdmin:

1. Buka [http://localhost:8082](http://localhost:8082)
2. Login dengan kredensial yang diset di .env:
    - Server: mysql
    - Username: sail
    - Password: password

Database dapat diakses dari host machine dengan:

-   Host: localhost
-   Port: 3307
-   Username: sail
-   Password: password

## Troubleshooting

### 1. Masalah Port

Jika ada error "port is already allocated" atau port sudah digunakan:

```bash
# 1. Cek port yang digunakan dan PID prosesnya
netstat -ano | findstr :8081
netstat -ano | findstr :8082
netstat -ano | findstr :3307

# 2. Matikan proses yang menggunakan port (ganti PID dengan nomor yang didapat)
taskkill /PID <PID> /F

# 3. Atau ubah port di file .env jika ingin menggunakan port lain:
APP_PORT=8083                    # Untuk aplikasi web (default: 8081)
FORWARD_DB_PORT=3308            # Untuk MySQL (default: 3307)
FORWARD_PHPMYADMIN_PORT=8084    # Untuk phpMyAdmin (default: 8082)

# 4. Sesuaikan juga APP_URL jika mengubah APP_PORT:
APP_URL=http://localhost:8083    # Sesuaikan dengan APP_PORT

# 5. Restart containers setelah mengubah port
docker compose down
docker compose up -d
```

Tips: Pastikan tidak ada aplikasi lain yang menggunakan port yang sama seperti Laragon, XAMPP, atau instance Laravel lain.

### 2. Masalah Permission

Jika ada masalah permission di storage:

```bash
docker exec atk-app chown -R www-data:www-data storage
docker exec atk-app chmod -R 775 storage
```

### 3. Masalah Cache

Jika ada masalah cache:

```bash
docker exec atk-app php artisan config:clear
docker exec atk-app php artisan cache:clear
docker exec atk-app php artisan view:clear
```

### 4. Container Tidak Berjalan

```bash
# Cek status container
docker ps -a

# Cek logs
docker logs atk-app
docker logs atk-db
docker logs atk-phpmyadmin

# Rebuild containers
docker compose down
docker compose build --no-cache
docker compose up -d
```

## Development Lokal (Tanpa Docker)

Jika ingin development tanpa Docker:

```bash
# Install dependencies
composer install
npm install

# Setup database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem-atk-paljaya
DB_USERNAME=root
DB_PASSWORD=

# Migrasi database
php artisan migrate

# Run development server
php artisan serve

# Build assets
npm run dev
```

## Struktur Direktori

```plaintext
sistem-mbp-paljaya/
├── app/                    # Logic aplikasi
├── config/                 # Konfigurasi
├── database/              # Migrasi & seeders
├── public/                # Asset publik
├── resources/             # Views & assets
├── routes/                # Route definition
├── storage/               # File uploads & logs
├── tests/                 # Unit/Feature tests
└── vendor/                # Dependencies
```
