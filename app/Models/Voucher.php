<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'deskripsi',
        'diskon',
        'tipe_diskon',
        'penggunaan_maksimal',
        'jumlah_digunakan',
        'tanggal_mulai',
        'tanggal_kadaluarsa',
        'aktif',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_kadaluarsa' => 'datetime',
        'aktif' => 'boolean',
    ];
}
