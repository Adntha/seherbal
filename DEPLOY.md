# 🌿 SeHerbal — Panduan Deploy ke Vercel

Panduan lengkap untuk mendeploy aplikasi **SeHerbal** (Laravel 10) ke Vercel dengan:
- **Database**: PlanetScale (MySQL Cloud, gratis)
- **Image Storage**: Cloudinary (gratis)
- **PHP Runtime**: `vercel-community/php`

---

## Prasyarat

- [x] Akun [Vercel](https://vercel.com) (gratis)
- [x] Akun [PlanetScale](https://planetscale.com) (gratis)
- [x] Akun [Cloudinary](https://cloudinary.com) (gratis)
- [x] Project sudah di-push ke GitHub di branch `deploy-vercel`

---

## Langkah 1 — Setup PlanetScale (Database)

1. Buka [planetscale.com](https://planetscale.com) → **Start with a free trial**
2. Buat database baru → nama: `seherbal`
3. Klik **Connect** → pilih **Laravel** sebagai framework
4. Salin semua kredensial (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
5. Pastikan branch database di PlanetScale adalah `main`

> **Catatan**: PlanetScale menggunakan SSL. Tambahkan `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt` di env Vercel.

---

## Langkah 2 — Setup Cloudinary (Image Storage)

1. Buka [cloudinary.com](https://cloudinary.com) → **Sign Up for Free**
2. Masuk ke Dashboard → klik **API Keys**
3. Salin: **Cloud Name**, **API Key**, **API Secret**
4. Format untuk env: `cloudinary://API_KEY:API_SECRET@CLOUD_NAME`

---

## Langkah 3 — Generate APP_KEY

Jalankan di terminal lokal (tidak perlu koneksi database):

```bash
php artisan key:generate --show
```

Salin outputnya (format: `base64:xxxx...`)

---

## Langkah 4 — Deploy ke Vercel

### Opsi A: Lewat GitHub (Recommended)

1. Push branch `deploy-vercel` ke GitHub:
   ```bash
   git add .
   git commit -m "chore: prepare Vercel deployment"
   git push origin deploy-vercel
   ```

2. Buka [vercel.com/new](https://vercel.com/new)
3. Import repository `seherbal` dari GitHub
4. **PENTING**: Set **Root Directory** ke `/` (biarkan default)
5. **PENTING**: Set **Framework Preset** ke `Other`
6. Klik **Deploy**

### Opsi B: Lewat Vercel CLI

```bash
npm i -g vercel
vercel login
vercel --prod
```

---

## Langkah 5 — Set Environment Variables di Vercel

Buka: **Vercel Dashboard** → Project `seherbal` → **Settings** → **Environment Variables**

Tambahkan satu per satu:

| Variable | Value |
|----------|-------|
| `APP_NAME` | `SeHerbal` |
| `APP_ENV` | `production` |
| `APP_KEY` | *(hasil dari step 3)* |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://seherbal.vercel.app` |
| `LOG_CHANNEL` | `stderr` |
| `LOG_LEVEL` | `error` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | *(dari PlanetScale)* |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | *(dari PlanetScale)* |
| `DB_USERNAME` | *(dari PlanetScale)* |
| `DB_PASSWORD` | *(dari PlanetScale)* |
| `MYSQL_ATTR_SSL_CA` | `/etc/ssl/certs/ca-certificates.crt` |
| `CACHE_DRIVER` | `array` |
| `SESSION_DRIVER` | `cookie` |
| `SESSION_LIFETIME` | `120` |
| `QUEUE_CONNECTION` | `sync` |
| `CLOUDINARY_URL` | `cloudinary://KEY:SECRET@CLOUD_NAME` |
| `GEMINI_API_KEY` | *(API key Gemini kamu)* |
| `ADMIN_EMAIL` | *(email admin)* |
| `ADMIN_PASSWORD` | *(password admin)* |

Setelah semua env diset → klik **Redeploy**

---

## Langkah 6 — Jalankan Migrasi Database

Setelah deploy berhasil dan env sudah diset, jalankan dari terminal lokal dengan env PlanetScale:

```bash
# Set env database ke PlanetScale dulu di .env lokal (sementara), lalu:
php artisan migrate --force
php artisan db:seed --force
```

Atau gunakan Vercel CLI untuk menjalankan perintah di deployment:
```bash
vercel env pull .env.production.local
php artisan migrate --force --env=production
```

---

## Troubleshooting

### ❌ Error: 500 Internal Server Error
- Pastikan `APP_KEY` sudah diset
- Cek logs di Vercel: **Project → Deployments → View Function Logs**

### ❌ Error: Database connection refused
- Pastikan `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` dari PlanetScale sudah benar
- Pastikan `MYSQL_ATTR_SSL_CA` sudah diset

### ❌ Error: 404 Not Found
- Pastikan `vercel.json` ada di root project
- Pastikan `api/index.php` ada

### ❌ Gambar tidak muncul
- Pastikan `CLOUDINARY_URL` format benar: `cloudinary://KEY:SECRET@CLOUD_NAME`
- Pastikan gambar sudah di-upload ulang via admin dashboard setelah deploy

---

## Struktur File Deployment

```
seherbal/
├── api/
│   └── index.php          ← Entry point Vercel (BARU)
├── vercel.json            ← Konfigurasi Vercel (BARU)
├── .vercelignore          ← File yang tidak diupload (BARU)
├── config/
│   ├── filesystems.php    ← Ditambah disk 'cloudinary'
│   └── cloudinary.php     ← Config Cloudinary (BARU)
├── app/
│   └── Http/Controllers/
│       ├── Admin/PlantController.php  ← Upload ke Cloudinary
│       └── Api/PlantController.php    ← Upload ke Cloudinary
└── .env.production.example ← Template env vars (BARU)
```
