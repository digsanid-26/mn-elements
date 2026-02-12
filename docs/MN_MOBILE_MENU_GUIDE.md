# MN Mobile Menu Widget Guide

## Overview
MN Mobile Menu (mn-mbmenu) adalah widget Elementor yang dioptimalkan untuk navigasi mobile dengan fitur hamburger menu yang lengkap dan kompatibel dengan Safari/iOS browser.

## Fitur Utama

### 1. **Hamburger Icon**
- 4 style icon: Default, Arrow Transform, Cross Transform, Minimal
- Pengaturan ukuran, warna, dan spacing
- Animasi smooth saat toggle

### 2. **Menu Position & Layout**
- **Posisi Menu**: Left, Right, Full Screen
- **Lebar Menu**: Dapat disesuaikan (px, %, vw)
- **Responsive**: Otomatis menyesuaikan di berbagai ukuran layar

### 3. **Animasi Kemunculan**
- **Slide**: Menu slide dari samping
- **Fade**: Menu fade in/out
- **Slide + Fade**: Kombinasi slide dan fade
- **Kecepatan Animasi**: Dapat disesuaikan (100-1000ms)

### 4. **Struktur Konten Menu**

#### Header Menu
- Upload logo
- Pengaturan ukuran logo (responsive)
- Background dan padding customizable
- Border styling

#### Main Menu
- Pilih WordPress menu yang sudah dibuat
- Support multi-level submenu
- Animasi expand/collapse untuk submenu
- Indikator visual untuk menu item yang aktif

#### Footer Menu
- Konten custom dengan WYSIWYG editor
- Ideal untuk copyright, social links, atau info tambahan
- Styling lengkap (typography, colors, padding)

### 5. **Style Controls**

#### Hamburger Icon Style
- Size (20-60px)
- Line height (1-6px)
- Color & hover color
- Padding

#### Menu Panel Style
- Background (solid/gradient)
- Box shadow
- Padding (responsive)

#### Overlay Style
- Background color dengan opacity
- Backdrop blur effect (modern browsers)

#### Menu Items Style
- Typography
- Text color (normal, hover, active)
- Background color (normal, hover, active)
- Padding & spacing
- Border & border radius

#### Submenu Style
- Typography terpisah
- Indent level
- Colors (normal & hover)

#### Close Button Style
- Size
- Color & hover color
- Animasi rotate on hover

## Optimalisasi Mobile & Safari/iOS

### 1. **Touch Optimization**
- `-webkit-tap-highlight-color: transparent` untuk menghilangkan highlight biru di iOS
- Touch event handling untuk swipe gestures
- Smooth scrolling dengan `-webkit-overflow-scrolling: touch`

### 2. **Safari/iOS Compatibility**
- Viewport height fix untuk iOS Safari (`100vh`, `100dvh`, `-webkit-fill-available`)
- Backdrop filter dengan prefix `-webkit-backdrop-filter`
- Overscroll behavior untuk prevent bounce
- Fixed positioning yang stabil

### 3. **Performance**
- Hardware acceleration dengan `transform` properties
- `requestAnimationFrame` untuk animasi smooth
- Debounced resize handler
- Efficient event delegation

### 4. **Accessibility**
- ARIA labels dan roles
- Keyboard navigation support (Tab, Enter, Escape)
- Focus trap saat menu terbuka
- Focus management yang proper

### 5. **Gesture Support**
- Swipe to close (left/right tergantung posisi menu)
- Touch-friendly tap targets (minimum 44x44px)
- Prevent scroll pada body saat menu terbuka

## Cara Penggunaan

### 1. Buat Menu di WordPress
```
Dashboard → Appearance → Menus
Buat menu baru atau gunakan menu yang sudah ada
```

### 2. Tambahkan Widget ke Elementor
```
1. Edit page dengan Elementor
2. Cari "MN Mobile Menu" di widget panel
3. Drag & drop ke section yang diinginkan
```

### 3. Konfigurasi Menu
```
Content Tab:
- Menu: Pilih WordPress menu
- Hamburger Icon: Pilih style dan posisi
- Layout: Atur posisi, lebar, dan animasi
- Header: Upload logo (optional)
- Footer: Tambah konten footer (optional)
```

### 4. Styling
```
Style Tab:
- Hamburger Icon: Ukuran, warna, padding
- Menu Panel: Background, shadow, padding
- Overlay: Warna dan opacity
- Menu Items: Typography, colors, spacing
- Submenu: Style untuk submenu
- Header/Footer: Styling untuk header dan footer
```

## Best Practices

### 1. **Mobile-First Design**
- Test di berbagai ukuran layar
- Gunakan responsive controls
- Pastikan tap targets cukup besar (min 44x44px)

### 2. **Performance**
- Jangan gunakan terlalu banyak menu items (max 10-15)
- Optimize logo image size
- Gunakan animasi speed yang reasonable (300-400ms)

### 3. **Accessibility**
- Pastikan contrast ratio yang baik
- Test dengan keyboard navigation
- Gunakan descriptive menu labels

### 4. **iOS/Safari Testing**
- Test di real device jika memungkinkan
- Check viewport height behavior
- Verify touch interactions
- Test swipe gestures

## Browser Compatibility

### Fully Supported
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- iOS Safari (12+)
- Chrome Mobile
- Samsung Internet

### Features with Graceful Degradation
- Backdrop filter (fallback to solid overlay)
- CSS Grid (fallback to flexbox)
- Custom scrollbar (fallback to default)

## Troubleshooting

### Menu tidak muncul
- Pastikan menu sudah dipilih di widget settings
- Check apakah menu memiliki items
- Verify JavaScript tidak error di console

### Animasi tidak smooth di iOS
- Pastikan menggunakan transform properties
- Check animation speed tidak terlalu cepat
- Verify hardware acceleration aktif

### Swipe gesture tidak bekerja
- Check touch events tidak di-block
- Verify minimum swipe distance
- Test di real device

### Menu terpotong di iOS Safari
- Gunakan viewport height fix yang sudah disediakan
- Check padding dan margin
- Verify overflow settings

## Customization Tips

### Custom CSS Classes
Widget menggunakan BEM naming convention:
```css
.mn-mbmenu-wrapper
.mn-mbmenu-toggle
.mn-mbmenu-panel
.mn-mbmenu-overlay
.mn-mbmenu-nav
.mn-mbmenu-list
.mn-mbmenu-header
.mn-mbmenu-footer
```

### JavaScript Events
Widget men-trigger custom events yang bisa di-hook:
```javascript
// Menu opened
jQuery(document).on('mn-mbmenu:opened', function(e, $menu) {
    // Your code
});

// Menu closed
jQuery(document).on('mn-mbmenu:closed', function(e, $menu) {
    // Your code
});
```

## Support & Updates

Widget ini fully compatible dengan:
- Elementor 3.0+
- WordPress 5.0+
- PHP 7.4+

Untuk bug reports atau feature requests, silakan hubungi developer.

---

**Version**: 1.0.0  
**Last Updated**: January 2026  
**Author**: Manakreatif
