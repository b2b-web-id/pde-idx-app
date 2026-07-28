# PDE @ IDX

Aplikasi Yii 2 Basic untuk mengelola data pelaku usaha berdasarkan data IDX (Bursa Efek Indonesia).

## Versi & Teknologi

| Komponen | Versi Terinstal |
|----------|----------------|
| **Yii2 Framework** | 2.0.38 |
| **PHP** | 8.4.x (runtime) — minimal 7.4 (composer) |
| **MySQL/MariaDB** | 11.x (MariaDB CLI) |
| **Composer** | Lihat `composer.lock` untuk versi binary |

### Dependency utama

| Paket | Versi |
|-------|-------|
| `yiisoft/yii2` | 2.0.38 |
| `yiisoft/yii2-bootstrap` | 2.0.11 |
| `yiisoft/yii2-swiftmailer` | 2.1.2 |
| `yiisoft/yii2-debug` | 2.1.x (dev) |
| `yiisoft/yii2-gii` | 2.1.x (dev) |
| `yiisoft/yii2-faker` | 2.0.x (dev) |
| `codeception/codeception` | 4.1.22 |
| `swiftmailer/swiftmailer` | v6.3.0 |
| `symfony/browser-kit` | >=2.7 |
| `fakerphp/faker` | v1.16.0 |
| `egulias/email-validator` | 3.1.2 |

Daftar lengkap tersedia di `composer.lock`.

## REQUIREMENTS

- PHP **>= 7.4**
- MySQL/MariaDB
- Apache dengan `mod_rewrite` aktif (atau Nginx dengan `try_files`)
- Composer

## Instalasi Cepat

```bash
# 1. Clone repo
git clone https://github.com/b2b-web-id/pde-idx-app.git
cd pde-idx-app

# 2. Install dependency
composer install

# 3. Salin dan konfigurasi db.php
cp config/db.php config/db.local.php
# Edit config/db.local.php dengan kredensial database Anda
# (config/db.php diabaikan oleh .gitignore untuk alasan keamanan)

# 4. Set environment variabel database (opsional, mengganti hardcoded password di db.php)
export DB_PASSWORD="your_db_password"

# 5. Akses aplikasi via web server dengan document root ke /web
# atau gunakan built-in PHP server:
php yii serve

# 6. Login
# Bawaan: admin/admin atau demo/demo
```

### Docker / Docker Compose (development)

```bash
docker-compose up -d
# Akses http://localhost:8000
```

### Vagrant

```bash
vagrant up
# Akses http://yii2basic.test
# (catatan: Vagrantfile memerlukan GitHub token di vagrant/config/vagrant-local.yml)
```

## Struktur Proyek

```
├── commands/         Console commands
├── config/           Application config (web, console, db, params)
├── controllers/      Web controllers (Individu, Perusahaan, Site)
├── models/           AR models (Individu, Perusahaan, User, LoginForm, ContactForm)
├── views/            PHP view templates (individu, perusahaan, site, layouts)
├── web/              Web root (index.php, assets, css, .htaccess)
├── mail/             Email templates
├── tests/            Codeception acceptance/functional/unit tests
├── runtime/          Logs, debug data (gitignored)
├── vendor/           Composer dependencies
├── docker-compose.yml  Docker development setup
├── Vagrantfile       Vagrant development setup
└── yii               Console entry script
```

## Database

Edit `config/db.php` dengan kredensial database asli. Karena file ini di-.gitignore, buat salinan lokal:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=pde_idx',
    'username' => 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => 'utf8',
];
```

Tabel yang dibutuhkan: `individu` dan `perusahaan` — skema sesuai dengan `models/Individu.php` dan `models/Perusahaan.php`.

## Testing

```bash
# Jalankan unit tests
vendor/bin/codeception run unit

# Jalankan functional tests
vendor/bin/codeception run functional

# Jalankan acceptance tests (membutuhkan browser driver aktif)
vendor/bin/codeception run acceptance
```

## Keamanan

- `web/index.php`: **YII_DEBUG dinonaktifkan secara bawaan** (false) untuk produksi
- Password pengguna di `models/User.php` disimpan dalam **bcrypt hash** (bukan plaintext)
- Modul debug & Gii dibatasi hanya ke `127.0.0.1` dan `::1`
- Cookie validation key ada di `config/web.php` — ganti dengan nilai unik/secure di produksi
- Database password dibaca dari environment variabel `DB_PASSWORD` dengan fallback kosong (untuk development lokal)
