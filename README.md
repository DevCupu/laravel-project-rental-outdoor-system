# Camping Rental System (Laravel 12)

Aplikasi penyewaan alat camping untuk Bontang Outdoor. Pengunjung bisa memesan alat tanpa login, sementara admin mengelola stok, booking, dan keuangan lewat panel internal.

## Ringkasan Fitur

### Halaman Publik
- **Beranda / katalog** – Menampilkan alat aktif lengkap dengan harga harian dan stok terlihat pada card.
- **Detail alat** – Route `/alat/{alat}` menunjukkan penjelasan alat, foto, dan tarif per hari.
- **Form booking** – Route `/booking` menyediakan wizard 5 langkah (data penyewa, jadwal, pemilihan alat jamak, ringkasan, konfirmasi).
- **Halaman sukses** – `/booking/sukses` merangkum kode booking, instruksi pembayaran cash/transfer, serta tombol WhatsApp untuk mengirim data booking.
- **Download bukti PDF** – `/booking/receipt/{booking}` adalah signed route yang aktif 24 jam setelah booking berhasil. File PDF berisi identitas penyewa, durasi sewa, tabel harga per alat (harga/hari × jumlah × hari), dan instruksi pembayaran sesuai metode.

### Panel Admin (Laravel Breeze Auth)
- **/admin/dashboard** – Insight cepat jumlah booking aktif, alat populer, dan status pembayaran.
- **/admin/alat** – CRUD alat dengan foto, harga, stok, dan status aktif.
- **/admin/booking** – List booking dengan filter status & aksi lanjutan.
- **/admin/booking/{id}/approve|cancel|pickup|return** – Aksi cepat untuk ubah status (approve, batalkan, tandai sudah diambil atau dikembalikan).
- **Profil admin** – Route `/profile` (edit, update, delete) disediakan oleh Breeze.

## Arsitektur Data

| Tabel | Deskripsi Singkat |
| --- | --- |
| `users` | Akun admin (Breeze). Saat ini hanya internal admin, tapi bisa dikembangkan untuk customer portal. |
| `alat` | Inventaris alat: nama, deskripsi, harga per hari, stok total, foto, status aktif. |
| `bookings` | Header booking: data penyewa, periode, total hari, total harga, status_booking, booking_code. |
| `booking_details` | Detail item per booking. Menyimpan `jumlah` dan `subtotal` (harga_per_hari × jumlah × total_hari). |
| `transaksis` | Catat pembayaran booking: metode (`cash` / `transfer`), status_pembayaran, nominal, bukti jika dibutuhkan. |

Relasi ERD: `alat` ← `booking_details` → `bookings` → `transaksis`, sedangkan admin login berada di `users`.

## Alur Booking End-to-End
1. Pengunjung membuka `/booking` dan mengisi data diri + jadwal.
2. User memilih beberapa alat sekaligus; sistem menghitung subtotal otomatis.
3. Setelah submit, `PublicController@storeBooking` menyimpan booking + detail + transaksi pending.
4. `PublicController@success` menarik booking terbaru, memunculkan instruksi transfer/cash sesuai pilihan, dan membuat signed URL PDF via `URL::temporarySignedRoute` (berlaku 24 jam).
5. Admin menerima booking di `/admin/booking` untuk diverifikasi, ubah status, dan menandai pembayaran.

## Konfigurasi Pembayaran & PDF
- Nilai default ada di [config/services.php](config/services.php) (`payment.cash_note`, `payment.transfer.*`). Sesuaikan lewat `.env`:
  - `PAYMENT_TRANSFER_BANK`, `PAYMENT_TRANSFER_ACCOUNT_NUMBER`, dll.
- PDF memakai `dompdf/dompdf` v2.0. View template berada di [resources/views/public/booking-receipt.blade.php](resources/views/public/booking-receipt.blade.php).
- Pastikan extension PHP `mbstring`, `gd`, dan `openssl` aktif di server.

## Struktur Direktori Penting

| Lokasi | Isi Aktual |
| --- | --- |
| `resources/views/layouts` | `layouts.public` (landing), `layouts.app` (admin). Hanya menampung kerangka HTML utama. |
| `resources/views/public` | `home`, `components`, `layouts`, `public/booking-success`, `public/booking-receipt`, dll. Dipisah per halaman agar mudah di-maintain. |
| `resources/views/admin` | Folder modul admin (`dashboard`, `alat`, `booking`, dll.) yang terhubung dengan controller masing-masing. |
| `resources/css` & `resources/js` | Asset Vite (`app.css`, `app.js`, `bootstrap.js`). Jangan masukkan logika PHP di sini. |
| `routes/web.php` | Seluruh route publik + admin dengan middleware & penamaan konsisten. |
| `routes/auth.php` | Otentikasi Breeze. |

Tips penulisan view:
- Gunakan Blade component/partial untuk elemen berulang (mis. card alat, alert status) agar tetap DRY.
- Simpan teks panjang / copywriting yang sering berubah di `lang/id/*.php` bila diperlukan multi-bahasa.
- Semua view terkait PDF tetap di folder `public` tetapi dipisahkan file-nya supaya styling tidak bercampur dengan halaman web.

## Route Penting

```text
GET  /                      -> PublicController@index (landing & katalog)
GET  /alat/{alat}           -> PublicController@show
GET  /alat/{alat}/availability -> PublicController@checkAvailability (Ajax)
GET  /booking               -> PublicController@bookingForm
POST /booking               -> PublicController@storeBooking
GET  /booking/sukses        -> PublicController@success
GET  /booking/receipt/{booking} (signed) -> PublicController@downloadReceipt

Route::prefix('admin')->middleware('auth'):
  GET  /dashboard           -> Admin\DashboardController@index
  Resource /alat            -> Admin\AlatController (CRUD)
  Resource /booking         -> Admin\BookingController
  GET  /booking/approve/{id}
  GET  /booking/cancel/{id}
  GET  /booking/pickup/{id}
  GET  /booking/return/{id}

Route::middleware('auth'):
  /profile (edit, update, delete) -> ProfileController
```

Konvensi yang dipakai:
- Prefix + middleware untuk memisahkan area admin.
- Penamaan route (`name('booking.store')`, `name('admin.booking.approve')`) supaya mudah dipanggil di Blade.
- Signed route untuk semua dokumen sensitif.

## Database Detail

### Tabel `alat`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| id | BIGINT | Primary key |
| nama_alat | VARCHAR | Contoh: Tenda Dome 2P |
| deskripsi | TEXT | HTML singkat diperbolehkan |
| harga_sewa_per_hari | INT | Harga rupiah/hari |
| stok_total | INT | Stok fisik |
| foto_path | TEXT | Path file |
| is_active | BOOLEAN | Tampil/tidak di katalog |
| timestamps | DATETIME | `created_at`, `updated_at` |

### Tabel `bookings`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| id | BIGINT |
| booking_code | STRING | Kode unik, ditampilkan di UI & PDF |
| nama_penyewa, no_hp, email | STRING |
| tanggal_mulai, tanggal_selesai | DATE |
| total_hari | INT | Di-hit oleh logic controller |
| total_harga | INT | Penjumlahan detail |
| status_booking | ENUM | pending, approved, cancelled, picked_up, returned |
| metode_pembayaran | ENUM | cash/transfer (melalui tabel `transaksis`) |

### Tabel `booking_details`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| booking_id | FK -> bookings |
| alat_id | FK -> alat |
| jumlah | INT |
| subtotal | INT | harga_per_hari × jumlah × total_hari |

### Tabel `transaksis`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| booking_id | FK |
| total | INT |
| metode_pembayaran | ENUM | cash / transfer |
| status_pembayaran | ENUM | menunggu_verifikasi, dp, lunas |
| tanggal_bayar | DATE | Opsional |

## Instalasi Cepat
1. `composer install`
2. `cp .env.example .env` lalu sesuaikan database & konfigurasi pembayaran (`PAYMENT_*`).
3. `php artisan key:generate`
4. `php artisan migrate --seed` (Seeder membuat admin dan alat dummy).
5. `npm install && npm run dev`
6. Jalankan lokal: `php artisan serve`

## Roadmap Fitur Berikutnya
1. **Pelacakan stok real-time** – Endpoint Ajax & validasi ketersediaan berdasarkan tanggal.
2. **Notifikasi WhatsApp otomatis** – Integrasi API Fonnte / WhatsApp Business untuk kirim pesan setelah booking/status change.
3. **Laporan keuangan bulanan** – Halaman grafik + ekspor CSV.
4. **Portal pelanggan opsional** – Customer bisa login untuk melihat riwayat & mengunduh ulang bukti.
5. **Scheduler pengingat pembayaran** – Cron job membatalkan booking pending >24 jam dan kirim notifikasi.

Prioritas: fokus pada 1–3 untuk kebutuhan operasional harian; fitur 4–5 menyusul setelah proses utama stabil.

---
**Tech Stack:** Laravel 12, Breeze, Tailwind + Vite, DOMPDF 2.0