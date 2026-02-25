<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'deskripsi',
        'diskon',
        'tipe_diskon',
        'penggunaan_maksimal',
        'jumlah_digunakan',
        'tanggal_kadaluarsa',
        'aktif',
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'datetime',
        'aktif' => 'boolean',
    ];
}
