# Test Chatbot API

Panduan untuk testing API chatbot setelah perbaikan.

## ✅ Perbaikan yang Dilakukan

### 1. Service Registration
**File:** `app/Providers/AppServiceProvider.php`

**Masalah:** ChatbotService tidak terdaftar di Service Container
**Solusi:** Mendaftarkan ChatbotService sebagai singleton

```php
$this->app->singleton(ChatbotService::class, function ($app) {
    return new ChatbotService();
});
```

### 2. Cache Cleared
Semua cache Laravel telah dibersihkan:
- ✅ Events cache
- ✅ Views cache
- ✅ Route cache
- ✅ Config cache

---

## 🧪 Testing API

### Test 1: Cek Routes
```bash
php artisan route:list --path=chatbot
```

**Expected Output:**
```
POST   api/chatbot/message
GET    api/chatbot/suggestions
GET    chatbot
```

### Test 2: Test API dengan cURL (Tanpa API Key)
```bash
curl -X POST http://localhost:8000/api/chatbot/message ^
  -H "Content-Type: application/json" ^
  -d "{\"message\": \"Halo\"}"
```

**Expected Response:**
```json
{
  "success": false,
  "message": "API Key Gemini belum dikonfigurasi. Silakan tambahkan GEMINI_API_KEY di file .env"
}
```

### Test 3: Test Suggestions Endpoint
```bash
curl http://localhost:8000/api/chatbot/suggestions
```

**Expected Response:**
```json
{
  "success": true,
  "suggestions": [
    "Apa khasiat temulawak?",
    "Tanaman apa yang bagus untuk batuk?",
    "Bagaimana cara mengolah jahe untuk obat?",
    "Tanaman herbal untuk menurunkan darah tinggi?",
    "Apa efek samping kunyit?"
  ]
}
```

### Test 4: Test dengan Browser
1. Jalankan server: `php artisan serve`
2. Buka: `http://localhost:8000/chatbot`
3. Pastikan UI muncul dengan baik

---

## 🔧 Troubleshooting

### Error: "Target class [App\Services\ChatbotService] does not exist"
**Solusi:**
```bash
composer dump-autoload
php artisan optimize:clear
```

### Error: "Class 'App\Services\ChatbotService' not found"
**Solusi:** Pastikan file `app/Services/ChatbotService.php` ada dan namespace benar

### Error: CORS atau 419 (CSRF Token Mismatch)
**Solusi:** Pastikan meta tag CSRF ada di blade template:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### API tidak merespons
**Solusi:**
1. Cek log: `storage/logs/laravel.log`
2. Pastikan server berjalan: `php artisan serve`
3. Test dengan Postman atau cURL

---

## ✅ Checklist Setelah Perbaikan

- [x] ChatbotService terdaftar di AppServiceProvider
- [x] Routes terdaftar dengan benar
- [x] Cache dibersihkan
- [ ] API Key Gemini sudah ditambahkan di .env
- [ ] Test endpoint /api/chatbot/message
- [ ] Test endpoint /api/chatbot/suggestions
- [ ] Test UI di browser

---

## 📝 Catatan

Jika semua test di atas berhasil, artinya API sudah berfungsi dengan baik. Tinggal tambahkan API Key Gemini untuk mengaktifkan fitur AI.

**Cara mendapatkan API Key:**
1. Kunjungi: https://makersuite.google.com/app/apikey
2. Login dengan Google
3. Create API Key
4. Copy dan paste ke `.env`
