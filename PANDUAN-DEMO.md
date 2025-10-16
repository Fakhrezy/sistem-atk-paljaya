# 🎯 PANDUAN DEMO SISTEM ATK PALJAYA

## ✅ CHECKLIST PERSIAPAN DEMO

### 🔧 Persiapan Teknis

-   [x] ngrok sudah terinstall
-   [x] TrustProxies dikonfigurasi
-   [x] Database berjalan dan migrasi selesai
-   [x] Server Laravel berjalan di port 8000
-   [ ] ngrok tunnel aktif
-   [ ] URL publik didapat dari ngrok dashboard

### 👤 Akun Demo yang Tersedia

```
Admin:
Email: admin@admin.com
Password: password

User:
Email: user@user.com
Password: password
```

## 🚀 LANGKAH MENJALANKAN DEMO

### Opsi 1: Menggunakan Script Otomatis

```cmd
# 1. Double-click file ini:
start-demo.bat

# 2. Tunggu hingga semua service running
# 3. Buka browser ke http://localhost:4040
# 4. Salin Public URL dari ngrok dashboard
# 5. Share URL tersebut untuk demo
```

### Opsi 2: Manual Step-by-step

```cmd
# 1. Start Laravel Server
php artisan serve --host=0.0.0.0 --port=8000

# 2. Buka terminal baru, start ngrok
ngrok http 8000

# 3. Buka ngrok dashboard
# Browser: http://localhost:4040

# 4. Salin "Forwarding URL"
# Contoh: https://abc123.ngrok-free.app
```

## 🌐 SAAT DEMO BERLANGSUNG

### URL yang Perlu Dibagikan

-   **Public URL**: https://xxxxx.ngrok-free.app (dari ngrok dashboard)
-   **Login**: https://xxxxx.ngrok-free.app/login

### Flow Demo yang Direkomendasikan

1. **Login sebagai Admin** (admin@admin.com)
2. **Dashboard Admin** - Overview sistem
3. **Daftar Barang** - CRUD dan export Excel
4. **Detail Monitoring** - Tracking barang
5. **Logout dan Login sebagai User** (user@user.com)
6. **User Dashboard** - Interface user
7. **Usulan Barang** - Request barang baru
8. **Pengambilan Barang** - Process pengambilan

### Fitur Unggulan untuk Dipresentasikan

✨ **Export Excel** - Format profesional dengan Times New Roman
✨ **Real-time Monitoring** - Tracking status barang
✨ **Multi-user System** - Admin dan User interface
✨ **Responsive Design** - Mobile friendly
✨ **Search & Filter** - Pencarian dan filter data
✨ **Custom Icons** - UI yang menarik

## ⚠️ TROUBLESHOOTING SAAT DEMO

### Jika ngrok menampilkan warning "Visit Site":

1. Klik tombol "Visit Site"
2. Atau gunakan ngrok authtoken untuk remove warning permanently

### Jika ada error 500:

```cmd
# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Jika database error:

1. Pastikan MySQL di Laragon running
2. Check koneksi di .env file

### Jika ngrok tunnel putus:

-   ngrok free = 2 jam limit
-   Restart ngrok akan dapat URL baru
-   Share URL baru ke audience

## 📱 TESTING CHECKLIST SEBELUM DEMO

### Backend Testing

-   [ ] Login admin berhasil
-   [ ] Login user berhasil
-   [ ] Dashboard load dengan benar
-   [ ] CRUD barang berfungsi
-   [ ] Export Excel download
-   [ ] Search dan filter aktif

### UI/UX Testing

-   [ ] All buttons clickable
-   [ ] Icons tampil dengan benar
-   [ ] Table responsive
-   [ ] Colors consistent (blue theme)
-   [ ] Navigation smooth

### Performance Testing

-   [ ] Page load < 3 detik
-   [ ] No console errors
-   [ ] Mobile responsive
-   [ ] Excel export < 10 detik

## 🛑 MENGHENTIKAN DEMO

```cmd
# Opsi 1: Script otomatis
stop-demo.bat

# Opsi 2: Manual
# Tekan Ctrl+C di Laravel terminal
# Tekan Ctrl+C di ngrok terminal
```

## 📊 DATA DEMO YANG SUDAH ADA

Sistem sudah memiliki:

-   Sample data barang
-   Sample monitoring records
-   Sample user accounts
-   Sample pengambilan data

Jika butuh data tambahan:

```cmd
php artisan db:seed
```

## 🎯 TIPS PRESENTASI

1. **Persiapkan Scenario** - Buat flow cerita yang menarik
2. **Backup Plan** - Siapkan screenshot jika koneksi bermasalah
3. **Timing** - Ingat ngrok free 2 jam limit
4. **Audience Engagement** - Beri kesempatan audience coba langsung
5. **Highlight Features** - Fokus pada fitur unggulan

## 🔐 SECURITY NOTES

⚠️ **Untuk Demo Saja:**

-   Jangan gunakan data production
-   Password demo sederhana (password)
-   ngrok URL temporary dan public
-   Setelah demo, stop semua service

**Good luck with your demo! 🚀**
