# Panduan Sistem

## Instalasi Docker

### Metode 1: Menggunakan Docker Desktop

1. Download dan install Docker Desktop dari https://www.docker.com/products/docker-desktop
2. Ikuti panduan instalasi default
3. Pastikan WSL 2 terinstal jika menggunakan Windows

### Metode 2: Instalasi Manual via WSL2

1. Install WSL2:

```powershell
wsl --install
```

2. Install Ubuntu di WSL2:

```powershell
wsl --install -d Ubuntu
```

3. Setelah Ubuntu terinstal, buka Ubuntu terminal dan jalankan:

```bash
# Update package list
sudo apt-get update

# Install prerequisites
sudo apt-get install \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg \
    lsb-release

# Add Docker's official GPG key
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# Set up stable repository
echo \
  "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Install Docker Engine
sudo apt-get update
sudo apt-get install docker-ce docker-ce-cli containerd.io

# Start Docker service
sudo service docker start

# Add your user to docker group
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.23.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

4. Restart terminal Ubuntu atau logout/login kembali

## Persiapan Project

1. Clone repository ini
2. Copy file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=sistem_atk
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Menjalankan Aplikasi

1. Build dan jalankan container:

```powershell
docker-compose up -d --build
```

2. Tunggu proses selesai, lalu cek status container:

```powershell
docker-compose ps
```

3. Masuk ke container app untuk menjalankan perintah artisan:

```powershell
docker-compose exec app bash
```

4. Di dalam container, jalankan migrasi database:

```bash
php artisan migrate
```

5. (Opsional) Jalankan seeder jika diperlukan:

```bash
php artisan db:seed
```

## Mengakses Aplikasi

-   Aplikasi dapat diakses di: http://localhost:8000
-   Database dapat diakses di:
    -   Host: localhost
    -   Port: 3306
    -   Username: sesuai DB_USERNAME di .env
    -   Password: sesuai DB_PASSWORD di .env

## Perintah Berguna

-   Menghentikan container:

```powershell
docker-compose down
```

-   Melihat log aplikasi:

```powershell
docker-compose logs -f
```

-   Mengakses database MySQL:

```powershell
docker-compose exec db mysql -u root -p
```

## Troubleshooting

1. Jika terjadi masalah permission:

```powershell
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

2. Jika perlu clear cache:

```powershell
docker-compose exec app php artisan optimize:clear
```

3. Jika database tidak dapat diakses:

-   Pastikan service MySQL sudah berjalan
-   Cek kredensial di file .env
-   Coba restart container:

```powershell
docker-compose restart
```


## Admin

- **Email:** `admin@paljaya.com`
- **Password:** `password`
