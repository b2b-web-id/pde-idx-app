# PDE @ IDX - Arsitektur

Dokumen ini menjelaskan struktur aplikasi pada branch `development` dan `master` saat ini.

## 1. Gambaran Umum

PDE @ IDX adalah aplikasi Yii2 Basic dengan pola MVC. Web request masuk melalui `web/index.php`, diproses oleh controller, menggunakan ActiveRecord/search model untuk mengakses MySQL/MariaDB, lalu dirender melalui view PHP.

```text
Browser
  -> web/index.php
  -> yii\web\Application
  -> controller
  -> model / ActiveRecord / database
  -> view
```

## 2. Komponen Utama

| Lapisan | Implementasi |
|---|---|
| Entry point | `web/index.php` |
| Framework | Yii2 Basic 2.0.38 |
| Database access | Yii2 ActiveRecord dan migration |
| Database | MySQL/MariaDB melalui PDO MySQL |
| Dependency | Composer, `composer.lock` |
| UI | Yii2 Bootstrap, GridView, DetailView, ActiveForm, Pjax |
| Test | Codeception unit, functional, acceptance |

## 3. Struktur Direktori

```text
commands/                 Console controller, termasuk `seed`
config/                   Konfigurasi aplikasi dan koneksi database
controllers/              Controller web
migrations/               Perubahan schema dan data referensi
models/                   ActiveRecord, search model, dan form model
views/                    Template halaman
web/                      Document root publik
tests/                    Test suite
docs/                     Dokumentasi teknis dan roadmap
runtime/                  Log/cache lokal, tidak di-version-control
vendor/                   Dependency Composer, tidak di-version-control
```

## 4. Alur Data

### 4.1 Individu dan Perusahaan

`Individu` dan `Perusahaan` menyimpan data master. Keduanya terhubung melalui `IndividuPerusahaan`, yang menyimpan:

- `INDIVIDU_ID` dan `PERUSAHAAN_ID`.
- Jabatan custom dan `jabatan_ref`.
- Status hubungan serta tanggal mulai/akhir.
- Flag komisaris independen dan keterangan.

Unique index pada pasangan individu/perusahaan mencegah duplikasi relasi. Foreign key menggunakan cascade delete.

### 4.2 Referensi IDX

`Sektor`, `Kbli`, `IdxKlasifikasi`, dan `BiroAdminEfek` menyediakan data referensi untuk `Perusahaan`. Migration `m240101000005` sampai `m240101000007` membuat hierarchy dan seed data awal.

### 4.3 Kepemilikan Perusahaan

`KepemilikanPerusahaan` adalah edge berarah dari `pemilik_entitas_id` ke `perusahaan_id`. Pemilik dapat berupa entitas individu, perusahaan, kelompok publik, saham treasury, atau entitas eksternal.

Anak perusahaan tidak dibuat sebagai tabel terpisah. Ia ditampilkan dari relasi ketika entitas pemilik adalah perusahaan. Setiap record menyimpan snapshot `tanggal_data`, `sumber_data`, persentase kepemilikan/hak suara, periode berlaku, dan referensi sumber. Unique key snapshot mencegah duplikasi tanpa menghapus histori.

### 4.4 Pengguna

`User` adalah ActiveRecord dan implementasi `IdentityInterface`. Password disimpan sebagai hash. User baru dari registrasi selalu mendapat role `user`; akun admin dibuat melalui `php yii seed` atau proses administrasi terkontrol.

## 5. Routing dan Controller

Pretty URL diatur di `config/web.php`.

| Route | Controller | Akses |
|---|---|---|
| `/` dan `/site/*` | `SiteController` | Publik atau user sesuai aksi |
| `/individu` | `IndividuController` | Index/view publik; mutasi admin |
| `/perusahaan` | `PerusahaanController` | Index/view publik; mutasi admin |
| `/individu-perusahaan` | `IndividuPerusahaanController` | Index/view publik; mutasi admin |
| `/kepemilikan-perusahaan` | `KepemilikanPerusahaanController` | Index/view publik; mutasi admin |
| `/register` | `SiteController::actionRegister` | Guest |

`AccessControl` membatasi operasi mutasi. `VerbFilter` membatasi delete ke HTTP POST.

## 6. Konfigurasi dan Environment

`config/db.php` membaca:

- `DB_DSN` untuk DSN database.
- `DB_USERNAME` untuk user database.
- `DB_PASSWORD` untuk password database.

`COOKIE_VALIDATION_KEY` dapat digunakan untuk menyediakan secret cookie. Web entry point saat ini default ke `YII_DEBUG=false` dan `YII_ENV=prod`; pengaturan development harus dilakukan secara eksplisit pada entry point atau konfigurasi deployment.

`.env` diabaikan Git, tetapi belum dimuat otomatis oleh aplikasi. Environment variable harus disediakan oleh shell, Docker, PHP-FPM, atau process manager.

## 7. Keamanan Saat Ini

- Password menggunakan `Yii::$app->security`.
- CSRF validation aktif untuk web request.
- Access control membatasi operasi administratif ke role `admin`.
- Debug dan Gii dibatasi ke localhost saat environment development aktif.
- Response menambahkan X-Frame-Options, X-Content-Type-Options, Referrer-Policy, HSTS, dan CSP pada non-debug response.
- Login memiliki pembatasan percobaan berbasis session.

Catatan: migration `login_attempts` sudah tersedia, tetapi implementasi rate limiting saat ini belum menggunakannya dan belum menjadi rate limit terpusat per IP. Ini merupakan pekerjaan fase keamanan berikutnya.

## 8. Migration dan Seed

Migration dijalankan berurutan oleh Yii:

```bash
php yii migrate --interactive=0
php yii seed
```

Migration harus idempoten pada level deployment: jangan mengubah migration yang sudah pernah dijalankan; buat migration baru untuk perubahan schema selanjutnya. Seed user memeriksa keberadaan username sebelum membuat data.

## 9. Testing dan Quality Gates

Perubahan minimum sebaiknya melewati:

```bash
php -l path/to/changed.php
vendor/bin/codecept run unit
vendor/bin/codecept run functional
```

Acceptance test dijalankan bila browser driver dan database test tersedia. Area yang saat ini membutuhkan coverage tambahan adalah migration, authorization, registrasi, login throttling, dan CRUD relasi.

## 10. Deployment Boundary

Hanya `web/` yang boleh menjadi document root. `config/`, `migrations/`, `runtime/`, dan source lain tidak boleh dapat diakses langsung oleh web server. Dependency dipasang dari `composer.lock` pada deployment; `vendor/` tidak dikirim melalui Git.

Untuk production, siapkan HTTPS, secret dari secret manager/environment, database backup, log rotation, health check, dan proses rollback migration.

Roadmap implementasi terurut tersedia di [`roadmap.md`](roadmap.md).
