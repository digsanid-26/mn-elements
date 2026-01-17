# MN Elements

Plugin Elementor kustom yang menyediakan kumpulan widget dan efek untuk memperkaya halaman web Anda dengan animasi dan kontrol yang menarik.

## Deskripsi

MN Elements adalah plugin WordPress yang dirancang khusus untuk Elementor (versi free maupun pro). Plugin ini menambahkan 29 widget kustom serta efek/style baru yang dibuat secara tertata rapi dan dapat bertambah di tiap versinya tanpa adanya konflik atau masalah satu sama lain.

## Versi Terbaru

**Version 2.0.8**

## Fitur Utama

### Widget Collection (29 Widgets)

#### Basic Widgets
- **MN Button**: Enhanced button dengan background animation, icon animation, position & alignment controls
- **MN Heading**: Dynamic heading dengan custom field support (ACF, JetEngine, WordPress Meta)
- **MN Image or Icon**: Flexible image/icon display widget

#### Content Widgets
- **MN Posts**: Advanced posts grid dengan pagination, quick view, archive query support
- **MN Infolist**: Manual list management dengan image, description, dan read more
- **MN Counter**: Animated counter dengan grid/list layout
- **MN Running Post**: Scrolling news ticker
- **MN Download**: File download manager dengan dynamic source
- **MN View**: File viewer popup untuk PDF, images, videos
- **MN Dynamic Tabs**: Tabs widget dengan static/dynamic content
- **MN Accordion**: Collapsible content sections
- **MN Office Hours**: Business hours display

#### Media Widgets
- **MN Video Playlist**: YouTube playlist dengan dynamic source support
- **MN SlideSwipe**: Template-based slider dengan Swiper.js
- **MN Image Comparison**: Before/after image comparison slider
- **MN Gallery**: Advanced image gallery dengan JetEngine support
- **MN Logolist**: Logo carousel/grid dengan dynamic source
- **MN Instafeed**: Instagram feed (manual/API)
- **MN Hero Slider**: Full-width hero slider
- **MN Dual Slider**: Synchronized dual slider

#### Social & Reviews
- **MN Gootesti**: Google reviews display
- **MN Social Reviews**: Multi-platform social reviews
- **MN Testimony**: Testimonial slider/grid

#### Navigation & UI
- **MN Sidepanel**: Off-canvas sidepanel dengan multiple triggers
- **MN Postnav**: Post navigation (prev/next)

#### WooCommerce
- **MN WooCart**: Mini cart dengan AJAX support
- **MN Woo Product Gallery**: Product image gallery

#### Communication
- **MN Wachat**: WhatsApp chat widget dengan working hours
- **MN Author**: Author box widget

### MNTriks Control
- **Entrance Animation**: Kontrol animasi masuk untuk Container dan Section
- **Animation Types**: Zoom Out, Zoom In, Fade In, Slide Up/Down/Left/Right
- **Customizable Settings**: Delay, Duration, dan Easing

### Element Manager
- **Widget Management**: Enable/disable individual widgets
- **Performance Optimization**: Only load active widgets
- **Admin Interface**: Modern UI dengan toggle switches

## Persyaratan

- WordPress 5.0 atau lebih baru
- Elementor 3.0 atau lebih baru
- PHP 7.4 atau lebih baru

## Instalasi

1. Upload folder `mn-elements` ke direktori `/wp-content/plugins/`
2. Aktifkan plugin melalui menu 'Plugins' di WordPress admin
3. Pastikan Elementor sudah terinstal dan aktif
4. Akses **Elementor > MN Elements** untuk mengelola widget aktif

## Struktur File

```
mn-elements/
├── mn-elements.php              # File utama plugin
├── includes/
│   ├── admin/
│   │   ├── element-manager.php  # Widget manager admin
│   │   └── settings.php         # Plugin settings
│   ├── widgets/                 # 29 widget files
│   ├── widgets-manager.php      # Widget registration
│   ├── container-extension.php  # MNTriks extension
│   └── assets.php               # Asset management
├── assets/
│   ├── css/                     # Widget stylesheets
│   └── js/                      # Widget scripts
├── docs/                        # Documentation
└── languages/                   # Translation files
```

## Teknologi yang Digunakan

- **Anime.js**: Library animasi JavaScript
- **Swiper.js**: Modern slider library
- **Intersection Observer API**: Viewport detection
- **Elementor Hooks**: Seamless integration
- **WordPress REST API**: Dynamic content

## Kompatibilitas

- ✅ Elementor Free
- ✅ Elementor Pro
- ✅ WordPress Multisite
- ✅ WooCommerce
- ✅ JetEngine
- ✅ ACF (Advanced Custom Fields)
- ✅ Responsive Design
- ✅ RTL Support
- ✅ Modern Browsers (Chrome, Firefox, Safari, Edge)

## Changelog

### Version 2.0.8
- **MN Button**: Added Position (left, center, right, stretch) and Alignment controls
- **Element Manager**: Complete widget management system
- **Performance**: Conditional widget loading

### Version 2.0.0
- **Major Release**: 29 widgets available
- **MN Instafeed**: Instagram feed widget
- **MN WooCart**: WooCommerce cart widget
- **MN Wachat**: WhatsApp chat with working hours
- **MN View**: File viewer popup
- **MN Download**: File download manager
- **Dynamic Source**: Support for ACF, JetEngine, WordPress Meta

### Version 1.0.0
- Initial release
- MNTriks Entrance Animation control
- Basic widget collection

## Dukungan

Untuk dukungan dan pertanyaan, silakan hubungi:
- Website: [https://digsan.id](https://digsan.id)
- GitHub: [https://github.com/digsanid-26/mn-elements](https://github.com/digsanid-26/mn-elements)

## Lisensi

Plugin ini dilisensikan di bawah GPL v2 atau yang lebih baru.

## Kontribusi

Kontribusi sangat diterima! Silakan buat pull request atau laporkan issue di repository GitHub.

---

**Dibuat dengan ❤️ oleh DigsanID**
