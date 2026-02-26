<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'tanggal_waktu_mulai',
        'tanggal_waktu_selesai',
        'lokasi',
        'kategori_id',
        'gambar',
        'status',
        'publish_at',
    ];

    protected $casts = [
        'tanggal_waktu_mulai'  => 'datetime',
        'tanggal_waktu_selesai' => 'datetime',
        'publish_at'           => 'datetime',
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}



