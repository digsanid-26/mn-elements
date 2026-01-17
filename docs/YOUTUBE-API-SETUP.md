# YouTube API Setup Guide untuk MN Video Playlist

Panduan lengkap untuk mengatur YouTube API dan menggunakan fitur auto-sync di MN Video Playlist widget.

## Daftar Isi
1. [Mendapatkan YouTube API Key](#mendapatkan-youtube-api-key)
2. [Mendapatkan YouTube Playlist ID](#mendapatkan-youtube-playlist-id)
3. [Mendapatkan YouTube Channel ID](#mendapatkan-youtube-channel-id)
4. [Konfigurasi Widget](#konfigurasi-widget)
5. [Troubleshooting](#troubleshooting)

---

## Mendapatkan YouTube API Key

### Langkah 1: Buat Project di Google Cloud Console

1. Buka [Google Cloud Console](https://console.developers.google.com/)
2. Login dengan akun Google Anda
3. Klik **"Select a project"** di bagian atas
4. Klik **"NEW PROJECT"**
5. Masukkan nama project (contoh: "My Website Videos")
6. Klik **"CREATE"**

### Langkah 2: Enable YouTube Data API v3

1. Setelah project dibuat, pastikan project Anda terpilih
2. Di sidebar kiri, klik **"APIs & Services"** → **"Library"**
3. Cari **"YouTube Data API v3"**
4. Klik pada hasil pencarian
5. Klik tombol **"ENABLE"**

### Langkah 3: Buat API Key

1. Di sidebar kiri, klik **"APIs & Services"** → **"Credentials"**
2. Klik **"+ CREATE CREDENTIALS"** di bagian atas
3. Pilih **"API key"**
4. API key Anda akan dibuat dan ditampilkan
5. **PENTING**: Salin dan simpan API key ini dengan aman
6. (Opsional) Klik **"RESTRICT KEY"** untuk keamanan:
   - Pilih **"HTTP referrers (web sites)"**
   - Tambahkan domain website Anda (contoh: `*.yourdomain.com/*`)
   - Di **"API restrictions"**, pilih **"Restrict key"**
   - Centang **"YouTube Data API v3"**
   - Klik **"SAVE"**

### Quota & Limits

- **Free Quota**: 10,000 units per hari
- **Playlist Request**: ~3 units per request
- **Channel Request**: ~3-5 units per request
- **Caching**: Widget menggunakan cache untuk mengurangi API calls

**Estimasi Penggunaan:**
- 1 playlist dengan 10 video = ~3 units
- Cache 6 jam = maksimal 4 requests per hari = ~12 units
- Anda bisa menampilkan 800+ playlist per hari dengan quota gratis

---

## Mendapatkan YouTube Playlist ID

### Metode 1: Dari URL Playlist

1. Buka playlist di YouTube
2. Lihat URL di address bar:
   ```
   https://www.youtube.com/playlist?list=PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf
   ```
3. Playlist ID adalah bagian setelah `list=`:
   ```
   PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf
   ```

### Metode 2: Dari Playlist Embed Code

1. Klik **"Share"** pada playlist
2. Klik **"Embed"**
3. Lihat kode embed, cari bagian `list=`:
   ```html
   <iframe src="https://www.youtube.com/embed/videoseries?list=PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf">
   ```
4. Copy ID setelah `list=`

### Format Playlist ID
- Selalu dimulai dengan **"PL"**
- Panjang: 34 karakter
- Contoh: `PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf`

---

## Mendapatkan YouTube Channel ID

### Metode 1: Dari URL Channel (Format Baru)

Jika URL channel Anda seperti ini:
```
https://www.youtube.com/channel/UCuAXFkgsw1L7xaCfnd5JJOw
```
Channel ID adalah: `UCuAXFkgsw1L7xaCfnd5JJOw`

### Metode 2: Dari Custom URL

Jika channel menggunakan custom URL (contoh: `youtube.com/@channelname`):

1. Buka halaman channel
2. Klik kanan → **"View Page Source"**
3. Cari `"channelId"` atau `"externalId"` di source code
4. Copy ID yang ditemukan

### Metode 3: Menggunakan Tool Online

1. Buka [YouTube Channel ID Finder](https://commentpicker.com/youtube-channel-id.php)
2. Paste URL channel Anda
3. Klik **"Find YouTube Channel ID"**
4. Copy Channel ID yang ditampilkan

### Format Channel ID
- Selalu dimulai dengan **"UC"**
- Panjang: 24 karakter
- Contoh: `UCuAXFkgsw1L7xaCfnd5JJOw`

---

## Konfigurasi Widget

### Setup YouTube Playlist Source

1. Tambahkan **MN Video Playlist** widget ke halaman Anda
2. Di panel **Video Management**:
   - **Source**: Pilih **"YouTube Playlist"**
   - **YouTube API Key**: Paste API key Anda
   - **YouTube Playlist ID**: Paste playlist ID
   - **Max Videos**: Set jumlah video (1-50)
   - **Cache Duration**: Pilih durasi cache (recommended: 6 Hours)

### Setup YouTube Channel Source

1. Tambahkan **MN Video Playlist** widget ke halaman Anda
2. Di panel **Video Management**:
   - **Source**: Pilih **"YouTube Channel"**
   - **YouTube API Key**: Paste API key Anda
   - **YouTube Channel ID**: Paste channel ID
   - **Max Videos**: Set jumlah video (1-50)
   - **Cache Duration**: Pilih durasi cache (recommended: 6 Hours)

### Layout Type (Semua Tetap Berfungsi)

Pilih layout yang Anda inginkan:
- **Video Top - Playlist Bottom**: Layout horizontal klasik
- **Video Left 75% - Playlist Right 25%**: Layout sidebar kanan
- **Video Right 75% - Playlist Left 25%**: Layout sidebar kiri
- **Loop Carousel with Modal**: Carousel dengan modal popup

### Cache Management

**Clear Cache Button:**
- Gunakan tombol **"Clear YouTube Cache"** untuk force refresh data
- Berguna setelah menambah video baru ke playlist/channel
- Data akan di-fetch ulang dari YouTube API

**Auto Update:**
- Widget otomatis fetch data baru setelah cache expired
- Tidak perlu manual refresh jika cache duration sudah habis
- Cache membantu mengurangi API calls dan meningkatkan performa

---

## Troubleshooting

### Error: "No videos found"

**Kemungkinan Penyebab:**
1. Playlist/Channel ID salah
2. Playlist kosong atau private
3. Channel tidak memiliki video public

**Solusi:**
- Verifikasi ID sudah benar
- Pastikan playlist/channel bersifat public
- Cek apakah ada video yang published

### Error: "Invalid API Key"

**Kemungkinan Penyebab:**
1. API key salah atau expired
2. YouTube Data API v3 belum di-enable
3. API key restrictions terlalu ketat

**Solusi:**
- Generate API key baru
- Pastikan YouTube Data API v3 sudah enabled
- Cek API restrictions di Google Cloud Console
- Pastikan domain Anda ada di allowed referrers

### Error: "Quota exceeded"

**Kemungkinan Penyebab:**
1. Sudah mencapai 10,000 units quota per hari
2. Terlalu banyak request dalam waktu singkat

**Solusi:**
- Tunggu hingga quota reset (midnight Pacific Time)
- Tingkatkan cache duration untuk mengurangi requests
- Pertimbangkan upgrade ke paid quota jika diperlukan

### Video tidak update otomatis

**Kemungkinan Penyebab:**
1. Cache masih aktif
2. Cache duration terlalu lama

**Solusi:**
- Klik tombol **"Clear YouTube Cache"**
- Kurangi cache duration (contoh: dari 24 jam ke 6 jam)
- Tunggu hingga cache expired secara otomatis

### Thumbnail tidak muncul

**Kemungkinan Penyebab:**
1. Video private atau unlisted
2. Thumbnail belum di-generate YouTube
3. Network/firewall blocking YouTube images

**Solusi:**
- Pastikan video bersifat public
- Tunggu beberapa saat setelah upload
- Cek network/firewall settings

---

## Best Practices

### 1. Cache Duration
- **Website Traffic Rendah**: 12-24 jam
- **Website Traffic Sedang**: 6-12 jam
- **Website Traffic Tinggi**: 1-6 jam
- **Development/Testing**: 1 jam

### 2. Max Videos
- **Homepage**: 6-10 videos
- **Dedicated Video Page**: 20-30 videos
- **Archive Page**: 30-50 videos

### 3. Security
- Restrict API key ke domain Anda
- Jangan share API key secara public
- Monitor usage di Google Cloud Console
- Set up billing alerts jika upgrade ke paid

### 4. Performance
- Gunakan cache duration yang optimal
- Jangan set max videos terlalu tinggi jika tidak perlu
- Clear cache hanya saat diperlukan
- Monitor API quota usage

---

## FAQ

**Q: Apakah saya perlu membayar untuk YouTube API?**
A: Tidak, quota gratis 10,000 units/hari sudah cukup untuk mayoritas website.

**Q: Berapa lama cache bertahan?**
A: Sesuai setting Cache Duration yang Anda pilih (1-24 jam).

**Q: Apakah video otomatis update saat saya upload video baru?**
A: Ya, setelah cache expired atau setelah Anda clear cache manual.

**Q: Bisakah saya menampilkan playlist dari channel orang lain?**
A: Ya, asalkan playlist bersifat public dan Anda punya playlist ID-nya.

**Q: Apakah layout type mempengaruhi API usage?**
A: Tidak, semua layout type menggunakan API calls yang sama.

**Q: Bagaimana cara monitoring API usage?**
A: Buka Google Cloud Console → APIs & Services → Dashboard → YouTube Data API v3

---

## Support

Jika Anda mengalami masalah:
1. Cek error log WordPress (WP_DEBUG)
2. Verifikasi semua ID dan API key
3. Test dengan playlist/channel berbeda
4. Hubungi support dengan detail error message

---

**Terakhir diupdate**: 2024
**Plugin Version**: 1.8.6+
