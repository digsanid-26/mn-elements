# MN Heading Text Animation Feature

## Overview

Fitur animasi teks MN Heading memungkinkan Anda membuat animasi menarik pada heading dengan berbagai efek per karakter atau per kata. Fitur ini mendukung berbagai trigger, loop, dan pengaturan timing yang dapat disesuaikan.

## Cara Penggunaan

### 1. Aktifkan Animasi Teks

1. Tambahkan widget MN Heading ke halaman Anda
2. Pada bagian "Heading Parts", aktifkan "Individual Style" untuk bagian teks yang ingin dianimasi
3. Aktifkan "Enable Text Animation"

### 2. Pengaturan Dasar

- **Animation Unit**: Pilih antara "Character" (karakter) atau "Word" (kata)
- **Animation Type**: Pilih jenis animasi yang diinginkan
- **Animation Trigger**: Kapan animasi akan dimulai
- **Delay Between Units**: Jeda antar karakter/kata (ms)
- **Animation Duration**: Durasi animasi per unit (ms)
- **Stagger Delay**: Jeda stagger antar unit (ms)

### 3. Tipe Animasi Tersedia

#### Animasi Dasar
- **Fade In**: Muncul perlahan
- **Slide Up/Down/Left/Right**: Muncul dari arah tertentu
- **Zoom In/Out**: Memperbesar/memperkecil
- **Rotate In**: Berputar masuk
- **Bounce In**: Memantul masuk

#### Animasi Lanjutan
- **Wave**: Efek gelombang
- **Typewriter**: Efek mengetik dengan kursor
- **Glitch**: Efek glitch digital
- **Neon**: Efuk neon flicker
- **Flip In**: Efek balik 3D
- **Elastic**: Efek elastis memantul

### 4. Trigger Animasi

- **On Page Load**: Animasi dimulai saat halaman dimuat
- **On Scroll**: Animasi dimulai saat elemen terlihat di viewport
- **On Hover**: Animasi dimulai saat hover
- **Manual**: Dikontrol secara manual via JavaScript

### 5. Pengaturan Loop

- **None**: Tidak ada loop
- **Infinite**: Loop tak terbatas
- **Specific Count**: Loop dengan jumlah tertentu (1-10)

#### Pengaturan Loop Lanjutan
- **Loop Delay**: Jeda antar loop (ms)
- **Reverse on Loop**: Mainkan animasi terbalik di akhir loop

### 6. Pengaturan Scroll Trigger

- **Scroll Threshold**: Persentase viewport untuk memicu animasi masuk (0.0-1.0, default 0.05)
- **Scroll Offset**: Offset dari viewport edge (px)
- **Play Once**: Mainkan sekali saja atau setiap kali masuk viewport
- **Reverse on Exit**: Mainkan terbalik saat keluar viewport (legacy)
- **Scroll Out Behavior**: Aksi saat keluar viewport:
  - **None**: Tidak ada aksi
  - **Reverse Animation**: Mainkan animasi terbalik
  - **Fade Out**: Fade out perlahan
  - **Slide Out**: Slide keluar dengan arah tertentu
  - **Zoom Out**: Zoom out mengecil
- **Slide Out Direction**: Arah slide out (Up, Down, Left, Right)
- **Scroll Out Duration**: Durasi animasi scroll out (ms)

### 🔄 Scroll Behavior Logic

**Viewport Target:**
- IntersectionObserver mengamati **widget MN Heading keseluruhan** (`.elementor-widget-mn-heading`)
- Bukan span individual atau bagian teks
- Memberikan trigger yang lebih akurat dan konsisten

**Scroll In (Masuk Viewport):**
- Trigger saat **widget MN Heading** muncul di viewport sesuai threshold
- Default threshold 0.05 (5% widget terlihat)
- Lower values = trigger lebih awal

**Scroll Out (Keluar Viewport):**
- Trigger pada deteksi **pertama** widget MN Heading keluar viewport
- Tidak peduli seberapa jauh widget ditinggalkan
- Hanya trigger jika Play Once = No

**Re-enter Viewport:**
- Jika Play Once = No, animasi akan diulang
- Scroll out classes di-reset otomatis

### 7. Pengaturan Timing

- **Initial Delay**: Jeda sebelum animasi pertama (ms)
- **Delay Between Units**: Jeda antar karakter/kata
- **Animation Duration**: Durasi per unit
- **Stagger Delay**: Jeda stagger untuk efek bertahap

## Contoh Penggunaan

### Contoh 1: Heading dengan Animasi Karakter Wave
```
- Animation Unit: Character
- Animation Type: Wave
- Trigger: On Scroll
- Delay: 50ms
- Duration: 600ms
- Stagger: 100ms
```

### Contoh 2: Heading dengan Loop Neon
```
- Animation Unit: Word
- Animation Type: Neon
- Trigger: On Load
- Loop: Infinite
- Loop Delay: 3000ms
- Duration: 800ms
```

### Contoh 3: Heading dengan Typewriter Effect
```
- Animation Unit: Character
- Animation Type: Typewriter
- Trigger: On Scroll
- Delay: 100ms
- Duration: 300ms
- Scroll Threshold: 0.5
```

### Contoh 4: Heading dengan Scroll Out Animation
```
- Animation Unit: Word
- Animation Type: Fade In
- Trigger: On Scroll
- Play Once: No
- Scroll Out Behavior: Slide Out
- Slide Out Direction: Down
- Scroll Out Duration: 500ms
- Scroll Threshold: 0.3
```

### Contoh 5: Heading dengan Interactive Scroll
```
- Animation Unit: Character
- Animation Type: Wave
- Trigger: On Scroll
- Play Once: No
- Scroll Out Behavior: Reverse Animation
- Scroll Threshold: 0.4
- Scroll Offset: 100px
```

## Kontrol via JavaScript

Anda dapat mengontrol animasi secara manual menggunakan JavaScript:

```javascript
// Trigger animasi manual
if (window.MNHeadingTextAnimation) {
    const element = document.querySelector('.mn-text-animation-enabled');
    window.MNHeadingTextAnimation.triggerAnimation(element);
}

// Reset animasi
window.MNHeadingTextAnimation.resetAnimation(element);

// Re-initialize semua animasi
window.MNHeadingAnimationReset();
```

## Event Kustom

Fitur ini memancarkan event kustom yang dapat Anda tangkap:

```javascript
element.addEventListener('mnTextAnimationComplete', function(e) {
    console.log('Animation completed for:', e.detail.element);
    // Lakukan sesuatu setelah animasi selesai
});
```

## Performa & Aksesibilitas

- Menggunakan IntersectionObserver untuk performa scroll trigger yang optimal
- Mendukung prefers-reduced-motion untuk aksesibilitas
- Tidak memblokir thread utama dengan animasi CSS-based
- Cleanup otomatis untuk mencegah memory leaks

## Tips & Best Practices

1. **Performa**: Gunakan animasi per kata untuk teks panjang, karakter untuk teks pendek
2. **Timing**: Sesuaikan stagger dengan kecepatan baca user
3. **Loop**: Hindari infinite loop untuk konten yang mengganggu
4. **Scroll**: Gunakan threshold yang wajar (0.3-0.5) untuk UX terbaik
5. **Mobile**: Pertimbangkan untuk mengurangi durasi animasi di mobile

## Troubleshooting

### Animasi tidak berjalan:
- Pastikan "Enable Text Animation" aktif
- Periksa console untuk error JavaScript
- Pastikan file JS terload dengan benar

### Performa lambat:
- Kurangi jumlah unit yang dianimasi
- Gunakan animasi kata bukan karakter untuk teks panjang
- Periksa pengaturan stagger yang terlalu kecil

### Animasi tidak terlihat:
- Periksa pengaturan timing (delay/duration)
- Pastikan trigger sesuai (scroll/load/hover)
- Verifikasi CSS tidak di-override oleh style lain
