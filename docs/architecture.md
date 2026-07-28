# PDE @ IDX — Architecture Document

**Tanggal:** 2025-07-28  
**Versi Yii2:** 2.0.38 (terinstal)  
**Runtime PHP:** 8.4.23  
**Database:** MySQL / MariaDB 11.x  

---

## 1. Gambaran Umum

PDE @ IDX adalah aplikasi web berbasis **Yii2 Basic** yang mengelola data pelaku usaha dari data IDX (Bursa Efek Indonesia). Aplikasi ini menggunakan arsitektur **MVC** (Model–View–Controller) khas Yii2 dan berjalan di atas web server Apache/Nginx dengan PHP-FPM atau mod_php.

---

## 2. Technology Stack

| Lapisan | Teknologi | Versi |
|---------|-----------|-------|
| Framework | Yii2 (Basic Template) | 2.0.38 |
| Runtime | PHP (CLI & FPM) | 8.4.x |
| Database | MySQL / MariaDB | 11.x |
| Web Server | Apache 2.4+ atau Nginx | — |
| Asset Packaging | Composer + Asset Packagist | — |
| Mail | SwiftMailer via Yii2 | 2.1.2 |
| Testing | Codeception | 4.1.22 |
| Container | Docker / Docker Compose | 3 |
| Vagrant | VirtualBox + Ubuntu 16.04 (legacy) | — |

---

## 3. Direktori & Struktur Proyek

```
pde-idx-app/
├── commands/                # Console controllers
│   └── HelloController.php  # Example console command
├── config/
│   ├── console.php          # Console app config
│   ├── db.php               # DB connection (gitignored)
│   ├── params.php           # Shared parameters
│   ├── test.php             # Test environment config
│   ├── test_db.php          # Test DB connection
│   └── web.php              # Web app config (main)
├── controllers/
│   ├── IndividuController.php  # CRUD for individu table
│   ├── PerusahaanController.py # CRUD for perusahaan table
│   └── SiteController.php      # Login, contact, about
├── models/
│   ├── Individu.php         # ActiveRecord for individu table
│   ├── Perusahaan.php       # ActiveRecord for perusahaan table
│   ├── User.php             # Identity component (hardcoded, dev only)
│   ├── LoginForm.php        # Login form model
│   └── ContactForm.php      # Contact form model
├── views/
│   ├── layouts/main.php     # Main layout with navbar
│   ├── individu/            # Individu CRUD views
│   ├── perusahaan/          # Perusahaan CRUD views
│   └── site/                # Home, login, contact, about, error
├── assets/
│   └── AppAsset.php         # Asset bundle (CSS/JS)
├── web/
│   ├── index.php            # Web entry point
│   ├── index-test.php       # Test entry point
│   ├── .htaccess            # URL rewriting
│   ├── css/site.css         # Custom stylesheet
│   └── assets/              # Composer-assigned assets
├── mail/
│   └── layouts/html.php     # Email HTML layout
├── tests/
│   ├── acceptance/          # Acceptance (Cest) tests
│   ├── functional/          # Functional tests
│   ├── unit/                # Unit tests (model tests)
│   └── _support/             # Tester classes
├── runtime/                 # Writable runtime (logs, cache, debug)
├── vendor/                  # Composer dependencies
├── widgets/
│   └── Alert.php            # Session flash alert widget
├── docker-compose.yml       # Docker dev environment
├── Vagrantfile              # Vagrant dev environment
├── composer.json            # PHP dependency manifest
├── composer.lock            # Locked dependency versions
└── yii                      # Console entry script
```

---

## 4. Konfigurasi Utama

### 4.1 `config/web.php`

- **ID:** `basic` → `PDE @ IDX`
- **Components:**
  - `request` — cookie validation key hardcoded (harus diganti di produksi)
  - `cache` — `FileCache`
  - `user` — `app\models\User` sebagai identity class, `enableAutoLogin` aktif
  - `mailer` — `SwiftMailer`, `useFileTransport => true` (dev mode)
  - `log` — `FileTarget` untuk error & warning, `traceLevel => 3` di YII_DEBUG
  - `urlManager` — `enablePrettyUrl => true`, `showScriptName => false`, **rules kosong**
- **Modul dev (YII_ENV_DEV):**
  - `debug` — diaktifkan, `allowedIPs` dibatasi `127.0.0.1`, `::1`
  - `gii` — diaktifkan, `allowedIPs` dibatasi `127.0.0.1`, `::1`

### 4.2 `config/db.php`

```php
'dsn'      => 'mysql:host=localhost;dbname=yii2basic',
'username' => 'root',
'password' => getenv('DB_PASSWORD') ?: '',
'charset'  => 'utf8',
```

> **Catatan:** `config/db.php` di-.gitignore. Setiap developer membuat konfigurasi DB lokal mereka sendiri.

### 4.3 `config/params.php`

```php
return [
    'company' => 'B2B.Web.ID',
    'senderEmail' => 'noreply@b2b.web.id',
    'senderName' => 'PDE @ IDX',
];
```

### 4.4 `web/index.php` — Entry Point

```php
defined('YII_DEBUG') or define('YII_DEBUG', false);   // Diproduct-kan
defined('YII_ENV') or define('YII_ENV', 'prod');        // Diproduct-kan
```

Developer dapat mengubah ke `true` / `'dev'` untuk lingkungan pengembangan lokal.

---

## 5. Model — ActiveRecord

### 5.1 `models/Individu`

| Kolom | Tipe | Catatan |
|-------|------|---------|
| `ID` | int (PK) | Auto-increment |
| `NAMA` | string(200) | **Required**, unique |
| `ALAMAT` | string(250) | — |
| `EMAIL` | string(100) | — |
| `TELEPON` | string(50) | — |
| `HP` | string(50) | — |
| `FAKS` | string(50) | — |
| `SITUS` | string(100) | — |
| `TANGGAL_LAHIR` | date | safe |
| `TEMPAT_LAHIR` | string(100) | — |
| `TANGGAL_UPDATE` | datetime | safe |

### 5.2 `models/Perusahaan`

| Kolom | Tipe | Catatan |
|-------|------|---------|
| `ID` | int (PK) | Auto-increment |
| `NAMA` | string(200) | **Required**, unique |
| `IDX_KODE` | string(4) | — |
| `ALAMAT` | string(250) | **Required** |
| `EMAIL` | string(50) | — |
| `TELEPON` | string(50) | — |
| `FAKS` | string(50) | — |
| `NPWP` | string(20) | — |
| `SITUS` | string(100) | — |
| `TANGGAL_AKTA` | date | safe |
| `USAHA_UTAMA` | string(250) | — |
| `SEKTOR` | string(250) | — |
| `KODE_KBLI` | string(5) | — |
| `TANGGAL_REKAM` | datetime | — |

### 5.3 `models\User`

- Mengimplements `\yii\web\IdentityInterface`
- **Identity class** yang terpasang di `config/web.php`
- **Hardcoded** untuk development (bukan database-backed)
- Password disimpan dalam **bcrypt hash** (`password_hash()`)
- Dua user bawaan: `admin/admin` dan `demo/demo`

---

## 6. Controller — Akses & CRUD

### 6.1 `SiteController`

| Aksi | Akses | Catatan |
|------|-------|---------|
| `index` | publik | Landing page |
| `login` | publik | `app\models\LoginForm` |
| `logout` | login | POST only via VerbFilter |
| `contact` | publik | `app\models\ContactForm` |
| `about` | publik | Boilerplate placeholder |
| `error` | publik | Error handler |
| `captcha` | publik | Captcha action |

### 6.2 `IndividuController` & `PerusahaanController`

Menggunakan pola CRUD standar: `index`, `view`, `create`, `update`, `delete`.

- **AccessControl:** `create`, `update`, `delete` hanya untuk `@` (user login)
- **VerbFilter:** `delete` hanya menerima POST
- **Index:** `ActiveDataProvider` tanpa search model (tanpa filter)
- **View:** `DetailView` widget
- **Create/Update:** `ActiveForm` widget

> **Catatan:** `PerusahaanController` menyembunyikan aksi `delete` dan `update` dari tombol aksi untuk user biasa di action column, dan membatasi hanya `{view}` untuk guest dan `{view} {update}` untuk user login. IndividuController menampilkan semua aksi.

### 6.3 `HelloController` (Console)

- Contoh command: `yii hello/index "message"`

---

## 7. Views — Widget & Layout

### 7.1 Layout (`views/layouts/main.php`)

- Bootstrap 4 navbar (inverse, fixed-top)
- Navigation: Home, Perusahaan, Individu, Login/Logout
- Breadcrumbs, Alert flash messages, Content
- Footer dengan copyright (B2B.Web IDX) dan `Yii::powered()`
- CSRF meta tags aktif

### 7.2 Widget yang Digunakan

| Widget | Sumber | Kegunaan |
|--------|--------|----------|
| `yii\grid\GridView` | Yii2 Core | Daftar data dengan aksi |
| `yii\widgets\DetailView` | Yii2 Core | Tampilan detail record |
| `yii\widgets\ActiveForm` | Yii2 Core | Form input & validasi |
| `yii\bootstrap\Nav` | Yii2 Bootstrap | Navigasi navbar |
| `yii\bootstrap\NavBar` | Yii2 Bootstrap | Header navigasi |
| `yii\widgets\Breadcrumbs` | Yii2 Core | Breadcrumb trail |
| `app\widgets\Alert` | Custom | Session flash alerts |
| `yii\captcha\Captcha` | Yii2 Captcha | Verification code |

---

## 8. Keamanan

| Aspek | Status |
|-------|--------|
| **YII_DEBUG** | Dinonaktifkan default di `web/index.php` |
| **Password storage** | Bcrypt hash (`password_hash()` via `Yii::$app->security`) |
| **Cookie validation key** | Hardcoded di `config/web.php` |
| **DB password** | Dibaca dari `DB_PASSWORD` env var |
| **Debug module IP restrict** | `127.0.0.1`, `::1` only |
| **Gii module IP restrict** | `127.0.0.1`, `::1` only |
| **CSRF** | Aktif secara bawaan Yii2 |
| **Access control** | RBAC sederhana (`@` = login user) |
| **Test config CSRF** | Dinonaktifkan (`enableCsrfValidation => false`) |

### 8.1 Langkah Keamanan yang Belum Diterapkan

- [ ] Cookie validation key diganti dengan env variabel di produksi
- [ ] Tidak ada RBAC berlapis (admin vs user biasa)
- [ ] Tidak ada rate limiting pada login
- [ ] Tidak ada HTTPS enforcement header

---

## 9. Testing

### 9.1 Test Pyramid

| Level | Suite | Cek |
|-------|-------|-----|
| **Unit** | `unit` | Model logic, validation, User identity |
| **Functional** | `functional` | Form submissions, request/response |
| **Acceptance** | `acceptance` | End-to-end browser scenarios (WebDriver/Firefox) |

### 9.2 Test Suites

- **Unit:** `Asserts` + `Yii2` (ORM, email, fixtures)
- **Functional:** `Filesystem` + `Yii2`
- **Acceptance:** `WebDriver` (Firefox, `http://127.0.0.1:8080/`) + `Yii2` (ORM)

### 9.3 Hasil yang Diketahui

- `HomeCest` sebelumnya gagal karena mengharapkan `'My Company'` bukan `'PDE @ IDX'` — sudah diperbaiki.
- Tidak ada unit test untuk model `Individu` maupun `Perusahaan`.
- Tidak ada acceptance test untuk operasi CRUD Individu/Perusahaan.

---

## 10. Lingkungan Pengembangan

### 10.1 Docker (Disarankan)

`docker-compose.yml` menggunakan `php:8.2-apache` dengan:
- Document root: `/var/www/html/web` (via `APACHE_DOCUMENT_ROOT`)
- Volume mount: `./:/var/www/html`
- Port: `8000:80`

### 10.2 Vagrant (Legacy)

`Vagrantfile` menggunakan `bento/ubuntu-16.04` (EOL) dengan:
- Box: `bento/ubuntu-16.04`
- Domain: `yii2basic.test`
- Membutuhkan GitHub token di `vagrant/config/vagrant-local.yml`
- Sinc folder: `./:/app`

> **Catatan:** Vagrant menggunakan Ubuntu 16.04 yang sudah EOL. Pertimbangkan untuk bermigrasi ke Ubuntu 22.04 atau lebih baru.

### 10.3 Konfigurasi Database Lokal

```bash
# Buat database
mysql -u root -e "CREATE DATABASE pde_idx;"

# Jalankan schema (contoh)
# Sesuaikan dengan tabel yang dibutuhkan oleh models/Individu.php dan models/Perusahaan.php
```

---

## 11. Halaman Belum Diterapkan

- **Hubungan** (Relationship) antara Individu dan Perusahaan — placeholder di halaman utama dengan teks *"Belum diterapkan."*
- Tidak ada model, controller, atau view untuk relasi Individu ↔ Perusahaan.
- Tidak ada `SearchModel` untuk `Individu` maupun `Perusahaan` — fitur pencarian/filter belum ada.

---

## 12. Histori Versi

| Versi Commit | Perubahan |
|-------------|-----------|
| `legacy/baseline-2025-07` | Baseline — seluruh fix P0 (disable debug, hash password, IP restrict, DB env) |
| `P0` (`447f9b8`) | Security fixes |
| `P1` (`dc82f6a`) | Test fixes, view cleanup, Yii2 composer target, Docker modernization |
| `P1 follow-up` (`f4d8d61`) | PHP >=7.4 requirement update |
| `447f9b8` | P0 security fixes |
| `35dcfd0` | Dependabot alert |
| `b5fd79d` | versi 0.1: data perusahaan & individu |
| `e552ff3` | Project init: Yii2-Basic-App |
