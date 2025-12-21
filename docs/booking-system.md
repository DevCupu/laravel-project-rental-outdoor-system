# Dokumentasi Sistem Booking Bontang Outdoor

Versi: Desember 2025

## Ringkasan Fitur
- Booking publik tanpa login dengan multi barang.
- Validasi stok otomatis dan booking pending maksimal 1×24 jam.
- Admin dapat memantau status booking, konfirmasi pembayaran, menandai serah-terima/pengembalian, dan menghapus data riwayat.
- Sistem transaksi terhubung dengan booking untuk pencatatan pembayaran (cash/transfer).

## Alur Pengguna Publik
1. Pengunjung membuka `/booking`, mengisi identitas (nama, WhatsApp) dan periode sewa.
2. Menambahkan satu atau banyak barang sekaligus.
3. Memilih metode pembayaran (cash atau transfer) dan mengirim form.
4. Sistem memvalidasi stok; jika tersedia, booking berstatus `pending` dibuat dan user diarahkan ke halaman sukses dengan kode booking.
5. User dapat menghubungi admin via WhatsApp menggunakan link otomatis.

## Alur Admin
1. Masuk ke dashboard (Laravel Breeze auth).
2. Pada halaman `admin/booking`, admin melihat ringkasan status (pending, confirmed, picked_up, returned, cancelled) dengan filter aktif/riwayat.
3. Admin dapat:
   - Melihat detail booking (alat, stok, pembayaran, info expiry).
   - Konfirmasi pembayaran pending → status `confirmed`.
   - Tandai barang diambil → status `picked_up` (stok otomatis dikurangi).
   - Tandai dikembalikan → status `returned` (stok otomatis dikembalikan).
   - Batalkan booking.
   - Menghapus booking pada tab riwayat (returned/cancelled) untuk kebersihan data.
4. Command `booking:expire-pending` dijalankan via scheduler untuk membatalkan booking pending yang sudah lebih dari 24 jam.

## Struktur Data Penting
- `bookings`: menyimpan informasi booking, kode unik, tanggal dan status.
- `booking_details`: relasi detail barang per booking (alat, jumlah, subtotal).
- `transaksis`: menampung total, status pembayaran, metode bayar.
- `alats`: daftar inventaris beserta stok total.

## Konfigurasi
- `config/booking.php` → `pending_expiry_minutes = 1440` (1×24 jam).
- `config/services.php` → informasi WhatsApp admin dan detail pembayaran (cash note, rekening transfer).

## Command & Scheduler
- `php artisan booking:expire-pending` → membatalkan booking pending melebihi batas 24 jam.
- Disarankan dijalankan lewat cron/scheduler setiap 10–15 menit.

## Rekomendasi Testing
- Jalankan `php artisan migrate --seed` untuk data awal.
- Gunakan `php artisan tinker` untuk membuat booking dummy.
- Tes alur booking publik (multi barang) dan cek stok di admin.
- Verifikasi auto-expiry dengan menjalankan command manual.

## Catatan Implementasi
- Penghapusan booking hanya boleh untuk status riwayat (returned/cancelled) dan dilakukan dalam transaksi agar detail & transaksi ikut terhapus.
- Booking publik menggunakan validasi stok dengan `calculateAvailableStock()` yang mengecek booking lain pada rentang tanggal sama.
- Semua pesan penting (expiry, status pembayaran) telah ditampilkan di UI admin/public.
