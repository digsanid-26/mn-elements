# MN Video Playlist - YouTube Auto-Sync Feature

Fitur auto-sync YouTube memungkinkan MN Video Playlist widget untuk otomatis mengambil dan menampilkan video dari YouTube Playlist atau YouTube Channel Anda.

## 🎯 Fitur Utama

### ✅ Dual YouTube Source
- **YouTube Playlist**: Tampilkan video dari playlist tertentu
- **YouTube Channel**: Tampilkan video dari channel (uploads)

### ✅ Auto-Update
- Video otomatis update sesuai cache duration
- Tidak perlu manual update widget saat ada video baru
- Clear cache button untuk force refresh

### ✅ Smart Caching
- Cache duration: 1, 6, 12, atau 24 jam
- Mengurangi API calls untuk performa optimal
- Hemat quota API YouTube

### ✅ Layout Compatibility
- Semua layout type tetap berfungsi:
  - Video Top - Playlist Bottom (Horizontal)
  - Video Left 75% - Playlist Right 25%
  - Video Right 75% - Playlist Left 25%
  - Loop Carousel with Modal

### ✅ Complete Video Data
- Video title (otomatis dari YouTube)
- Video description (otomatis dari YouTube)
- Video duration (otomatis dari YouTube)
- Video thumbnail (otomatis dari YouTube)

---

## 🚀 Quick Start

### 1. Dapatkan YouTube API Key
```
1. Buka: https://console.developers.google.com/
2. Buat project baru
3. Enable "YouTube Data API v3"
4. Buat API Key di Credentials
5. Copy API Key
```

### 2. Dapatkan Playlist/Channel ID

**Playlist ID** (dari URL):
```
https://www.youtube.com/playlist?list=PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf
                                      ↑
                              Playlist ID (dimulai dengan PL)
```

**Channel ID** (dari URL):
```
https://www.youtube.com/channel/UCuAXFkgsw1L7xaCfnd5JJOw
                                ↑
                        Channel ID (dimulai dengan UC)
```

### 3. Konfigurasi Widget

1. Tambahkan **MN Video Playlist** widget
2. **Video Management** panel:
   - Source: Pilih "YouTube Playlist" atau "YouTube Channel"
   - YouTube API Key: Paste API key Anda
   - Playlist/Channel ID: Paste ID yang sesuai
   - Max Videos: 10 (atau sesuai kebutuhan)
   - Cache Duration: 6 Hours (recommended)

3. **Layout** panel:
   - Pilih layout type yang diinginkan
   - Konfigurasi show/hide options
   - Set autoplay options

4. **Style** tab:
   - Customize sesuai design Anda
   - Semua style controls tetap berfungsi

---

## 📊 API Quota & Performance

### Free Quota
- **10,000 units per hari** (gratis)
- 1 playlist request = ~3 units
- 1 channel request = ~3-5 units

### Estimasi Penggunaan
```
Cache 6 jam = 4 requests/hari = ~12 units
Cache 12 jam = 2 requests/hari = ~6 units
Cache 24 jam = 1 request/hari = ~3 units
```

**Kesimpulan**: Dengan cache 6 jam, Anda bisa menampilkan **800+ playlists** per hari dengan quota gratis!

### Best Practices
- Gunakan cache duration yang sesuai traffic website
- Jangan set max videos terlalu tinggi jika tidak perlu
- Clear cache hanya saat benar-benar diperlukan
- Monitor usage di Google Cloud Console

---

## 🔄 Auto-Update Workflow

```
1. User mengunjungi halaman
   ↓
2. Widget cek cache
   ↓
3a. Cache valid → Tampilkan dari cache (cepat)
3b. Cache expired → Fetch dari YouTube API
   ↓
4. Update cache dengan data baru
   ↓
5. Tampilkan video playlist
```

### Kapan Video Update?
- Otomatis setelah cache duration habis
- Manual dengan klik "Clear YouTube Cache"
- Setelah save/update widget settings

---

## 🎨 Use Cases

### 1. Company YouTube Channel
```
Source: YouTube Channel
Channel ID: Your company channel
Max Videos: 12
Cache: 6 hours
Layout: Video Top - Playlist Bottom
```
**Result**: Tampilkan 12 video terbaru dari channel perusahaan

### 2. Product Tutorial Playlist
```
Source: YouTube Playlist
Playlist ID: Product tutorials playlist
Max Videos: 20
Cache: 12 hours
Layout: Video Left - Playlist Right
```
**Result**: Dedicated tutorial page dengan playlist sidebar

### 3. Video Gallery Carousel
```
Source: YouTube Playlist
Playlist ID: Portfolio/showcase playlist
Max Videos: 30
Cache: 24 hours
Layout: Loop Carousel with Modal
```
**Result**: Infinite carousel dengan modal popup

### 4. Latest Videos Widget
```
Source: YouTube Channel
Channel ID: Your channel
Max Videos: 6
Cache: 6 hours
Layout: Video Top - Playlist Bottom
```
**Result**: Homepage widget dengan 6 video terbaru

---

## 🛠️ Troubleshooting

### Video tidak muncul
✅ **Cek**:
- API Key sudah benar?
- Playlist/Channel ID sudah benar?
- Playlist/Channel bersifat public?
- YouTube Data API v3 sudah enabled?

### Video tidak update
✅ **Solusi**:
- Klik "Clear YouTube Cache"
- Kurangi cache duration
- Tunggu cache expired

### Quota exceeded
✅ **Solusi**:
- Tunggu hingga quota reset (midnight Pacific Time)
- Tingkatkan cache duration
- Kurangi jumlah widget yang menggunakan API

---

## 📚 Documentation

Untuk panduan lengkap, lihat:
- [YouTube API Setup Guide](./YOUTUBE-API-SETUP.md)

---

## 🆚 Comparison: Manual vs YouTube Sync

| Feature | Manual | YouTube Playlist | YouTube Channel |
|---------|--------|------------------|-----------------|
| Setup | Easy | Medium | Medium |
| Update | Manual | Auto | Auto |
| Video Data | Manual entry | Auto from YouTube | Auto from YouTube |
| Maintenance | High | Low | Low |
| API Required | No | Yes | Yes |
| Best For | Fixed content | Curated playlists | Latest uploads |

---

## ✨ Benefits

### Untuk Content Creator
- ✅ Video otomatis sync dengan YouTube
- ✅ Tidak perlu update manual di website
- ✅ Konsisten dengan YouTube channel
- ✅ Save time & effort

### Untuk Developer
- ✅ Easy integration dengan YouTube
- ✅ Smart caching system
- ✅ Error handling & logging
- ✅ Maintain existing layouts

### Untuk End User
- ✅ Always up-to-date content
- ✅ Fast loading (cached)
- ✅ Professional presentation
- ✅ Seamless experience

---

## 🔮 Future Enhancements

Planned features:
- [ ] Multiple playlist support
- [ ] Hashtag filtering
- [ ] Video search integration
- [ ] Custom sorting options
- [ ] Load more pagination
- [ ] Lightbox integration

---

## 📝 Notes

- Fitur ini **tidak menggantikan** source Manual dan Dynamic (Posts)
- Semua source dapat digunakan sesuai kebutuhan
- Layout type dan style controls tetap sama untuk semua source
- Cache disimpan di WordPress transients (database)

---

**Version**: 1.8.6+
**Last Updated**: 2024
**Status**: ✅ Production Ready
