# PDE @ IDX

Aplikasi Yii2 untuk mengelola data individu, perusahaan, dan hubungan kepengurusan berdasarkan data IDX (Bursa Efek Indonesia).

## Status Saat Ini

Fitur yang tersedia:

- CRUD dan pencarian data `Individu`.
- CRUD dan pencarian data `Perusahaan`.
- Relasi `Individu` <-> `Perusahaan`, termasuk jabatan, status, periode, dan penanda komisaris independen.
- Snapshot kepemilikan entitas-ke-perusahaan untuk merepresentasikan pemegang saham dan anak perusahaan.
- Referensi sektor, KBLI, klasifikasi IDX, dan biro administrasi efek.
- Login berbasis database, registrasi user, bcrypt password hashing, dan role `admin`/`user`.
- Akses CRUD relasi dan data utama dibatasi untuk administrator.
- CSRF protection, security response headers, dan pembatasan percobaan login berbasis session.

## Teknologi

| Komponen | Versi / Catatan |
|---|---|
| PHP | 8.4.x pada lingkungan pengembangan saat ini |
| Yii2 | 2.0.38 |
| Database | MySQL atau MariaDB |
| Dependency manager | Composer |
| Testing | Codeception 4.1.22 |
| Web server | Apache/Nginx atau PHP built-in server |

Versi dependency lengkap dikunci di `composer.lock`.

## Persyaratan

- PHP yang memenuhi constraint pada `composer.json`.
- Extension PHP yang dibutuhkan Yii2 dan driver `pdo_mysql`.
- MySQL/MariaDB.
- Composer.

## Instalasi Lokal

```bash
git clone https://github.com/b2b-web-id/pde-idx-app.git
cd pde-idx-app
composer install

mysql -u root -e "CREATE DATABASE pde;"

export DB_DSN='mysql:host=localhost;dbname=pde'
export DB_USERNAME='root'
export DB_PASSWORD=''

php yii migrate --interactive=0
php yii seed
php -S localhost:8000 -t web
```

Buka `http://localhost:8000`. `config/db.php` membaca `DB_DSN`, `DB_USERNAME`, dan `DB_PASSWORD` dari environment variable, dengan fallback untuk development lokal. File `.env` diabaikan, tetapi aplikasi tidak memuat `.env` secara otomatis; gunakan shell, process manager, atau library dotenv untuk memasukkan nilainya.

Dependency dan runtime tidak disimpan di Git. Jika `vendor/autoload.php` belum tersedia, jalankan `composer install` kembali.

### Akun Development

`php yii seed` membuat akun berikut jika belum ada:

| Username | Password | Role |
|---|---|---|
| `admin` | `admin` | `admin` |
| `demo` | `demo` | `user` |

Ganti password dan hapus akun default sebelum deployment.

## Database dan Migration

Migration membuat atau memperluas tabel berikut:

- `user` dan `login_attempts`.
- `individu`, `perusahaan`, dan `individu_perusahaan`.
- `sektor`, `kbli`, dan `idx_klasifikasi`.
- `biro_admin_efek` serta kolom referensi IDX pada `perusahaan`.
- `entitas` dan `kepemilikan_perusahaan` untuk pemegang saham individu, perusahaan, kelompok publik, treasury, serta histori snapshot.

Perintah yang umum digunakan:

```bash
php yii migrate
php yii migrate/history
php yii migrate/redo 1
```

> Pastikan backup database tersedia sebelum menjalankan migration pada lingkungan bersama atau production.

## Struktur Proyek

```text
commands/                 Console commands, termasuk seed user
config/                   Konfigurasi web, console, database, dan parameter
controllers/              Controller site, individu, perusahaan, dan relasi
migrations/               Schema dan seed data referensi IDX
models/                   ActiveRecord, search model, dan form model
views/                    Template halaman dan form
web/                      Document root dan entry point aplikasi
tests/                    Unit, functional, dan acceptance tests
docs/                     Dokumentasi arsitektur dan roadmap
runtime/                  Log/cache lokal, diabaikan Git
vendor/                   Dependency Composer, diabaikan Git
```

## Testing

```bash
vendor/bin/codecept run unit
vendor/bin/codecept run functional
vendor/bin/codecept run acceptance
```

Acceptance test membutuhkan browser driver dan aplikasi yang sedang berjalan. Test database harus menggunakan konfigurasi database test yang terpisah.

## Keamanan

- `web/index.php` default ke `YII_DEBUG=false` dan `YII_ENV=prod`.
- Password disimpan sebagai hash melalui `Yii::$app->security`.
- CSRF validation aktif di web application.
- CRUD dibatasi dengan `AccessControl`; operasi administrasi memerlukan role `admin`.
- Debug dan Gii, jika diaktifkan pada environment development, dibatasi ke localhost.
- Cookie validation key dapat diisi melalui `COOKIE_VALIDATION_KEY`.
- Security headers ditambahkan pada response non-debug.

Sebelum production, wajib mengganti credential default, menyediakan cookie key yang kuat, menggunakan HTTPS, membatasi database user, dan meninjau CSP serta logging.

## Roadmap Fase Berikutnya

Prioritas yang disarankan ada di [`docs/roadmap.md`](docs/roadmap.md). Urutan terdekat:

1. Stabilkan schema dan coverage test untuk migration, autentikasi, dan CRUD relasi.
2. Ganti role sederhana dengan RBAC Yii2 dan alur approval perubahan data.
3. Bangun pipeline import/validasi data IDX dengan histori dan idempotensi.
4. Tingkatkan pencarian, filter, export, dan audit trail untuk workflow operasional.
5. Siapkan observability, backup, deployment, dan hardening production.

## Dokumentasi Lanjutan

- [Arsitektur](docs/architecture.md)
- [Roadmap fase berikutnya](docs/roadmap.md)
