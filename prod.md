# Publish flow

## 1. Clone repository di server produksi

```bash
git clone https://github.com/Fakhrezy/sistem-mbp-paljaya.git
cd sistem-mbp-paljaya
```

## 2. Setup environment produksi

```bash
cp .env.example .env
```

Edit file .env dengan kredensial produksi (database, mail, dsb.).

## 3. Build dan jalankan containers

```bash
docker-compose -f docker-compose.prod.yml up -d --build
```

## 4. Setup Laravel di dalam container

```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan storage:link
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```
