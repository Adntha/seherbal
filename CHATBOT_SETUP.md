# Setup Chatbot AI dengan Gemini

Panduan lengkap untuk mengaktifkan fitur chatbot AI tanaman herbal.

## 📋 Langkah-langkah Setup

### 1. Dapatkan API Key Gemini (Gratis)

1. Kunjungi [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Login dengan akun Google Anda
3. Klik **"Create API Key"**
4. Copy API key yang dihasilkan

### 2. Konfigurasi Environment

Buka file `.env` di root project Anda dan tambahkan:

```env
GEMINI_API_KEY=your_api_key_here
```

Ganti `your_api_key_here` dengan API key yang sudah Anda dapatkan.

### 3. Jalankan Server

```bash
php artisan serve
```

### 4. Akses Chatbot

Buka browser dan kunjungi:
```
http://localhost:8000/chatbot
```

## 🎯 Cara Menggunakan

1. **Ketik pertanyaan** tentang tanaman herbal di kolom input
2. **Klik tombol kirim** atau tekan Enter
3. **Tunggu respons** dari AI (ditandai dengan animasi typing)
4. **Gunakan quick suggestions** untuk pertanyaan cepat

### Contoh Pertanyaan:
- "Apa khasiat temulawak?"
- "Tanaman apa yang bagus untuk batuk?"
- "Bagaimana cara mengolah jahe untuk obat?"
- "Tanaman herbal untuk menurunkan darah tinggi?"
- "Apa efek samping kunyit?"

## 🔧 Troubleshooting

### Error: "API Key Gemini belum dikonfigurasi"
**Solusi:** Pastikan Anda sudah menambahkan `GEMINI_API_KEY` di file `.env`

### Error: Rate Limit
**Solusi:** Gemini API gratis memiliki limit 15 request per menit. Tunggu sebentar lalu coba lagi.

### Chatbot tidak merespons
**Solusi:** 
1. Periksa koneksi internet
2. Pastikan API key valid
3. Cek log error di `storage/logs/laravel.log`

## 📱 Fitur

✅ **AI-Powered** - Menggunakan Google Gemini AI  
✅ **Context-Aware** - Memahami 35 tanaman herbal dari dataset  
✅ **Modern UI** - Desain dark mode dengan animasi smooth  
✅ **Responsive** - Berfungsi di desktop dan mobile  
✅ **Real-time** - Typing indicator dan auto-scroll  
✅ **Quick Suggestions** - Pertanyaan cepat yang sering ditanyakan  

## 🎨 Kustomisasi

### Mengubah Warna
Edit file `public/css/chatbot.css` pada bagian `:root`:

```css
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    /* ... */
}
```

### Menambah Quick Suggestions
Edit method `getQuickSuggestions()` di `app/Services/ChatbotService.php`

## 🔒 Keamanan

- ✅ CSRF Protection
- ✅ Input validation (max 1000 karakter)
- ✅ XSS prevention
- ✅ API key disimpan di environment variable

## 📊 API Endpoints

### POST `/api/chatbot/message`
Mengirim pesan ke chatbot

**Request:**
```json
{
  "message": "Apa khasiat jahe?"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Jahe memiliki banyak khasiat..."
}
```

### GET `/api/chatbot/suggestions`
Mendapatkan quick suggestions

**Response:**
```json
{
  "success": true,
  "suggestions": [
    "Apa khasiat temulawak?",
    "..."
  ]
}
```

## 📝 Catatan Penting

- **Free Tier Limits**: Gemini API gratis memiliki limit 15 requests per menit
- **Dataset**: Chatbot menggunakan data dari `database/data/dataset_tanaman_herbal.json`
- **Bahasa**: Chatbot dioptimalkan untuk Bahasa Indonesia
- **Disclaimer**: Selalu konsultasikan dengan ahli kesehatan untuk pengobatan

## 🚀 Next Steps

Setelah chatbot berjalan, Anda bisa:
1. Menambah data tanaman herbal di dataset
2. Mengintegrasikan dengan halaman utama website
3. Menambah fitur history chat
4. Implementasi user authentication

---

**Selamat mencoba! 🌿**
