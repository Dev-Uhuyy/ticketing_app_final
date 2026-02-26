# Dokumentasi Manajemen Metode Pembayaran untuk Superadmin

Dokumentasi ini menjelaskan fitur manajemen metode pembayaran yang hanya dapat diakses oleh pengguna dengan peran **superadmin**. Fitur ini memungkinkan superadmin untuk membuat, melihat, memperbarui, dan menghapus metode pembayaran yang digunakan dalam sistem.

---

## 🔐 Akses & Hak Istimewa
- Rute tertutup dengan middleware `auth` dan `superadmin`.
- Semua URL berada di bawah prefix `admin` dan nama rute `superadmin.*`.
- Hanya akun dengan role **superadmin** yang dapat mengakses antarmuka atau API.

> **Catatan:** middleware `superadmin` menolak akses dari role lain, seperti `pengelola`.

## 🗂 Struktur Database
Tabel yang dipakai adalah `payment_methods`.
Migrasi: `database/migrations/2026_02_26_create_payment_methods_table.php`.

Kolom utama (tidak ada kolom `status`):

| Kolom            | Tipe       | Keterangan                                                  |
|------------------|------------|-------------------------------------------------------------|
| `id`             | bigint     | Primary key                                                |
| `name`           | string     | Nama tampilan (mis. "Bank Transfer")                     |
| `code`           | string     | Kode unik (lowercase & underscore)                         |
| `description`    | text       | Penjelasan tambahan (opsional)                             |
| `icon`           | string     | Path ke file ikon (opsional)                               |
| `is_active`      | boolean    | Menandakan apakah metode aktif atau tidak                  |
| `fee_percentage` | integer    | Persentase biaya ekstra (mis. 2 = 2%)                      |
| `timestamps`     | timestamps | `created_at` dan `updated_at`                              |

Model Eloquent `App\Models\PaymentMethod` sudah mengisi `$fillable` untuk semua kolom di atas dan melakukan casting `is_active` ke boolean.

## 💾 Seeder Standar
File `database/seeders/PaymentMethodSeeder.php` menambahkan contoh:
- Bank Transfer
- Kartu Kredit
- E-Wallet
- Cicilan

Jalankan seeder dengan:
```bash
php artisan db:seed --class=PaymentMethodSeeder
```

## 🚦 Rute & Kontroller
Rute di `routes/web.php`:
```php
Route::middleware('superadmin')->prefix('admin')->name('superadmin.')->group(function () {
    // ...
    Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::put('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    // ...
});
```

`PaymentMethodController` (`App\Http\Controllers\Admin\PaymentMethodController`) menyediakan CRUD:
- `index()` – daftar
- `create()` – form tambah
- `store(Request)` – validasi nama, buat kode, simpan
- `show(PaymentMethod)` – rincian
- `edit(PaymentMethod)` – form edit
- `update(Request, PaymentMethod)` – validasi & simpan ulang
- `destroy(PaymentMethod)` – hapus

> Setelah tindakan sukses, controller mengarahkan kembali dengan pesan flash.

## 🖥️ Tampilan (Views)
Folder: `resources/views/admin/payment_methods` (buat jika belum ada).
File standar:
- `index.blade.php` – tabel daftar metode
- `create.blade.php` – form menambahkan
- `edit.blade.php` – form mengedit
- `show.blade.php` – tampilan rincian

## 🧪 Contoh API/Permintaan
**Tambah Metode:**
```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token_superadmin>" \
  -d '{"name":"Virtual Account","description":"VA pembayaran"}' \
  https://example.com/admin/payment-methods
```

**Perbarui Metode:**
```bash
curl -X PUT \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token_superadmin>" \
  -d '{"name":"Bank Transfer Manual","fee_percentage":1}' \
  https://example.com/admin/payment-methods/3
```

**Hapus Metode:**
```bash
curl -X DELETE \
  -H "Authorization: Bearer <token_superadmin>" \
  https://example.com/admin/payment-methods/5
```

## ✅ Tips Praktis
1. **Kode otomatis**: `code` dihasilkan dari nama; hindari duplikasi.
2. **Nonaktifkan**: atur `is_active=false` untuk menonaktifkan tanpa menghapus.
3. **Ikon**: simpan gambar di `public/assets/images` lalu taruh path di kolom.
4. **Validasi**: pastikan `fee_percentage` >= 0.
5. **Konsistensi UI**: gunakan layout blade yang sama dengan modul lain (kategori, event).

---

Dokumentasi ini disusun 26 Februari 2026 sesuai permintaan table tanpa kolom status.
