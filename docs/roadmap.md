# Roadmap Fase Berikutnya

Roadmap ini memprioritaskan pengurangan risiko operasional sebelum menambah banyak fitur UI.

## Fase 1 - Stabilitas dan Kontrak Data

Target: aplikasi dapat di-install, di-migrate, dan diuji ulang secara konsisten.

- Tambahkan migration test pada database MySQL/MariaDB bersih.
- Tambahkan fixture dan unit test untuk `User`, `RegisterForm`, `LoginForm`, dan `IndividuPerusahaan`.
- Tambahkan functional test untuk guest, user biasa, dan admin.
- Validasi foreign key, unique constraint, timezone, dan format tanggal.
- Tambahkan fixture dan test untuk snapshot kepemilikan, termasuk self-link, duplikasi snapshot, dan histori tanggal.
- Pisahkan konfigurasi development/test/production secara eksplisit.
- Naikkan constraint PHP di `composer.json` agar sesuai runtime yang benar-benar didukung, setelah kompatibilitas diverifikasi.

Definition of done: `composer install`, migration, seed, dan test suite dapat dijalankan pada environment baru tanpa langkah manual yang tersembunyi.

## Fase 2 - Authorization dan Audit

Target: perubahan data dapat dikendalikan dan ditelusuri.

- Migrasikan role string sederhana ke RBAC Yii2.
- Definisikan permission untuk read, create, update, delete, import, dan approve.
- Pisahkan operator input, reviewer, dan administrator.
- Gunakan tabel `login_attempts` untuk rate limiting terpusat berdasarkan IP dan username, dengan cleanup berkala.
- Tambahkan audit trail untuk perubahan data perusahaan, individu, relasi, dan user.
- Tambahkan soft delete atau status arsip bila kebutuhan bisnis mengharuskan histori tetap utuh.

Definition of done: setiap perubahan sensitif memiliki actor, timestamp, nilai sebelum/sesudah, dan aturan approval yang teruji.

## Fase 3 - Import Data IDX

Target: data IDX dapat dimuat ulang secara aman dan berulang.

- Tetapkan format import resmi, mapping kolom, encoding, dan aturan normalisasi.
- Buat console command import dengan dry-run dan laporan error per baris.
- Pastikan pipeline import memanggil sinkronisasi `Entitas` atau menggunakan service yang sama dengan ActiveRecord.
- Gunakan staging table sebelum merge ke tabel master.
- Tambahkan idempotency key atau natural key untuk mencegah duplikasi.
- Simpan batch import, checksum sumber, waktu import, dan jumlah record berhasil/gagal.
- Sediakan rollback atau quarantine untuk data yang gagal validasi.

Definition of done: file yang sama dapat di-import dua kali tanpa menggandakan data dan hasil import dapat diaudit.

## Fase 4 - Workflow Operasional

Target: pengguna dapat menemukan, memeriksa, dan mengekspor data secara efisien.

- Sempurnakan filter nama, kode IDX, sektor, KBLI, jabatan, status, dan periode.
- Tambahkan pagination dan eager loading yang terukur untuk mencegah N+1 query.
- Tambahkan export CSV/XLSX dengan permission dan batas ukuran.
- Tambahkan halaman detail perusahaan yang merangkum individu, jabatan, klasifikasi, dan histori.
- Tambahkan validasi konflik periode jabatan dan duplikasi entitas.
- Tambahkan ownership tree dari `KepemilikanPerusahaan`, termasuk perhitungan kepemilikan tidak langsung.
- Tambahkan UI CRUD snapshot kepemilikan yang sudah tersedia ke halaman detail perusahaan.
- Tambahkan API read-only bila integrasi eksternal memang diperlukan.

Definition of done: workflow pencarian dan review data dapat diselesaikan tanpa akses langsung ke database.

## Fase 5 - Production Readiness

Target: aplikasi dapat dioperasikan dengan aman dan terukur.

- Tambahkan health check untuk aplikasi dan database.
- Standarkan deployment berbasis `composer.lock` dan migration gate.
- Tambahkan log rotation, error correlation ID, metrics dasar, dan alert.
- Aktifkan HTTPS, secure cookie, HSTS yang benar, dan CSP tanpa `unsafe-eval` bila kompatibel.
- Dokumentasikan backup/restore database dan prosedur rollback.
- Perbarui Docker image, hapus konfigurasi legacy yang tidak digunakan, dan evaluasi penggantian SwiftMailer yang sudah deprecated.

Definition of done: deployment, observability, backup restore, dan rollback diuji pada staging.

## Urutan yang Disarankan

Mulai dari Fase 1. Jangan membangun import besar atau API sebelum kontrak data, authorization, dan test migration stabil. Fase 2 harus selesai sebelum membuka workflow approval atau akses multi-user yang lebih luas.
