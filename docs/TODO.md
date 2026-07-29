# TODO List

## Fase 1 - Stabilitas dan Kontrak Data (Sudah selesai)
- [x] Migration test pada database MySQL/MariaDB bersih
- [x] Fixture dan unit test untuk User, RegisterForm, LoginForm, IndividuPerusahaan
- [x] Functional test untuk guest, user biasa, dan admin
- [x] Validasi foreign key, unique constraint, timezone, dan format tanggal
- [x] Fixture dan test untuk snapshot kepemilikan, termasuk self-link, duplikasi snapshot, dan histori tanggal
- [x] Pisahkan konfigurasi development/test/production secara eksplisit
- [x] Naikkan constraint PHP di composer.json agar sesuai runtime yang benar-benar didukung

## Fase 2 - Authorization dan Audit
- [ ] Migrasikan role string sederhana ke RBAC Yii2
- [ ] Definisikan permission untuk read, create, update, delete, import, dan approve
- [ ] Pisahkan operator input, reviewer, dan administrator
- [ ] Gunakan tabel login_attempts untuk rate limiting terpusat berdasarkan IP dan username, dengan cleanup berkala
- [ ] Tambahkan audit trail untuk perubahan data perusahaan, individu, relasi, dan user
- [ ] Tambahkan soft delete atau status arsip bila kebutuhan bisnis mengharuskan histori tetap utuh

## Fase 3 - Import Data IDX
- [ ] Tetapkan format import resmi, mapping kolom, encoding, dan aturan normalisasi
- [ ] Buat console command import dengan dry-run dan laporan error per baris
- [ ] Pastikan pipeline import memanggil sinkronisasi Entitas atau menggunakan service yang sama dengan ActiveRecord
- [ ] Gunakan staging table sebelum merge ke tabel master
- [ ] Tambahkan idempotency key atau natural key untuk mencegah duplikasi
- [ ] Simpan batch import, checksum sumber, waktu import, dan jumlah record berhasil/gagal
- [ ] Sediakan rollback atau quarantine untuk data yang gagal validasi

## Fase 4 - Workflow Operasional (Sebagian sudah selesai)
- [x] Sempurnakan filter nama, kode IDX, sektor, KBLI, jabatan, status, dan periode (free-text search added)
- [ ] Tambahkan pagination dan eager loading yang terukur untuk mencegah N+1 query
- [ ] Tambahkan export CSV/XLSX dengan permission dan batas ukuran
- [ ] Tambahkan halaman detail perusahaan yang merangkum individu, jabatan, klasifikasi, dan histori
- [ ] Tambahkan validasi konflik periode jabatan dan duplikasi entitas
- [ ] Tambahkan ownership tree dari KepemilikanPerusahaan, termasuk perhitungan kepemilikan tidak langsung
- [ ] Tambahkan UI CRUD snapshot kepemilikan yang sudah tersedia ke halaman detail perusahaan
- [ ] Tambahkan API read-only bila integrasi eksternal memang diperlukan

## Fase 5 - Production Readiness
- [ ] Tambahkan health check untuk aplikasi dan database
- [ ] Standarkan deployment berbasis composer.lock dan migration gate
- [ ] Tambahkan log rotation, error correlation ID, metrics dasar, dan alert
- [ ] Aktifkan HTTPS, secure cookie, HSTS yang benar, dan CSP tanpa unsafe-eval bila kompatibel
- [ ] Dokumentasikan backup/restore database dan prosedur rollback
- [ ] Perbarui Docker image, hapus konfigurasi legacy yang tidak digunakan, dan evaluasi penggantian SwiftMailer yang sudah deprecated