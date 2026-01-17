# MN Elements

Plugin Elementor kustom yang menyediakan kumpulan widget dan efek untuk memperkaya halaman web Anda dengan animasi dan kontrol yang menarik.

## Deskripsi

MN Elements adalah plugin WordPress yang dirancang khusus untuk Elementor (versi free maupun pro). Plugin ini menambahkan widget-widget serta efek/style baru yang dibuat secara tertata rapi dan dapat bertambah di tiap versinya tanpa adanya konflik atau masalah satu sama lain.

## Fitur Utama

### MNTriks Control
- **Entrance Animation**: Kontrol animasi masuk yang dapat diterapkan pada Container dan Section
- **Zoom Out**: Animasi zoom keluar saat elemen muncul
- **Multiple Animation Types**: Zoom In, Fade In, Slide Up/Down/Left/Right
- **Customizable Settings**: Delay, Duration, dan Easing yang dapat disesuaikan

## Persyaratan

- WordPress 5.0 atau lebih baru
- Elementor 3.0 atau lebih baru
- PHP 7.4 atau lebih baru

## Instalasi

1. Upload folder `mn-elements` ke direktori `/wp-content/plugins/`
2. Aktifkan plugin melalui menu 'Plugins' di WordPress admin
3. Pastikan Elementor sudah terinstal dan aktif

## Cara Penggunaan

### Menggunakan MNTriks Entrance Animation

1. Buka Elementor Editor
2. Pilih Container atau Section yang ingin diberi animasi
3. Buka tab **Advanced**
4. Scroll ke bagian **MNTriks**
5. Aktifkan **Entrance Animation**
6. Pilih **Animation Type** (default: Zoom Out)
7. Atur **Animation Delay** (ms) - waktu tunda sebelum animasi dimulai
8. Atur **Animation Duration** (ms) - durasi animasi
9. Pilih **Animation Easing** - jenis easing animasi
10. Simpan dan lihat hasilnya di frontend

### Jenis Animasi yang Tersedia

- **Zoom Out**: Elemen mulai dari ukuran besar kemudian mengecil ke ukuran normal
- **Zoom In**: Elemen mulai dari ukuran kecil kemudian membesar ke ukuran normal
- **Fade In**: Elemen muncul dengan efek fade
- **Slide Up**: Elemen masuk dari bawah
- **Slide Down**: Elemen masuk dari atas
- **Slide Left**: Elemen masuk dari kanan
- **Slide Right**: Elemen masuk dari kiri

## Struktur File

```
mn-elements/
├── mn-elements.php          # File utama plugin
├── includes/
│   ├── container-extension.php  # Ekstensi untuk Container/Section
│   └── assets.php              # Manajemen asset
├── assets/
│   ├── css/
│   │   ├── mn-elements-frontend.css  # Style frontend
│   │   └── mn-elements-editor.css    # Style editor
│   └── js/
│       ├── mn-elements-frontend.js   # JavaScript frontend
│       ├── mn-elements-editor.js     # JavaScript editor
│       └── lib/
│           ├── anime.min.js          # Library animasi
│           └── intersection-observer.js  # Polyfill observer
└── languages/                # File terjemahan
```

## Pengembangan

Plugin ini dibangun dengan arsitektur yang dapat diperluas. Untuk menambahkan fitur baru:

1. Buat file ekstensi baru di folder `includes/`
2. Daftarkan ekstensi di `mn-elements.php`
3. Tambahkan asset yang diperlukan
4. Update dokumentasi

## Teknologi yang Digunakan

- **Anime.js**: Library animasi JavaScript yang ringan dan powerful
- **Intersection Observer API**: Untuk deteksi elemen yang masuk viewport
- **Elementor Hooks**: Untuk integrasi yang seamless dengan Elementor

## Kompatibilitas

- ✅ Elementor Free
- ✅ Elementor Pro
- ✅ WordPress Multisite
- ✅ Responsive Design
- ✅ Modern Browsers (Chrome, Firefox, Safari, Edge)

## Changelog

### Version 1.4.8
- **MN Dynamic Tabs Widget - Major Enhancement**
  - ✅ **Content Slider Feature**: Tambahan fitur slider horizontal seperti MN SlideSwipe
  - ✅ **Slides Controls**: 
    - Slides to Show (1-6 slides)
    - Slides to Scroll (1-4 slides)
    - Gap control untuk jarak antar slides
  - ✅ **Navigation Controls**:
    - Show Navigation toggle (prev/next buttons)
    - Show Dots toggle (dot indicators)
    - Navigation buttons dengan disable states
    - Clickable dots dengan active states
  - ✅ **Layout Enhancement**:
    - Otomatis berubah ke layout horizontal saat slider aktif
    - Transform animation dengan translateX() untuk smooth sliding
    - CSS custom properties untuk dynamic styling
  - ✅ **Direction & Alignment Controls - FIXED**:
    - Direction control (Horizontal/Vertical) sekarang bekerja dengan !important CSS
    - Alignment control (Start/Center/End/Stretch) sekarang bekerja dengan !important CSS
    - Proper visual feedback untuk semua kombinasi direction/alignment
  - ✅ **Mobile Accordion Enhancement**:
    - Mobile accordion mendukung content slider
    - Auto-reset slider transforms untuk mobile layout
    - Desktop restore functionality saat kembali ke desktop
    - Touch-friendly accordion headers
  - ✅ **JavaScript Improvements**:
    - Enhanced slider functionality dengan nextSlide(), prevSlide(), goToSlide()
    - updateSliderControls() untuk button states dan active dots
    - Mobile responsive detection dan auto-switch
    - Autoplay dengan pause on hover functionality
  - ✅ **CSS Enhancements**:
    - Responsive slider layout dengan flexbox
    - Dynamic width calculation berdasarkan slides to show
    - Mobile-first responsive design
    - Smooth transitions dan animations

### Version 1.0.0
- Initial release
- MNTriks Entrance Animation control
- Support untuk Container dan Section
- 7 jenis animasi entrance
- Kontrol delay, duration, dan easing

## Dukungan

Untuk dukungan dan pertanyaan, silakan hubungi:
- Website: [https://manakreatif.com](https://manakreatif.com)
- Email: support@manakreatif.com

## Lisensi

Plugin ini dilisensikan di bawah GPL v2 atau yang lebih baru.

## Kontribusi

Kontribusi sangat diterima! Silakan buat pull request atau laporkan issue di repository GitHub.

---

**Dibuat dengan ❤️ oleh Manakreatif**
