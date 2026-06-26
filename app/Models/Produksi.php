<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $fillable = [
        'kode_produksi',
        'tanggal_produksi',
        'produk_id',
        'jumlah_produksi',
        'jumlah_gagal',
        'satuan_id',
        'karyawan_id',
        'keterangan',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_produksi' => 'date',
            'jumlah_produksi' => 'integer',
            'jumlah_gagal' => 'integer',
            'jumlah_bersih' => 'integer',
            'verified_at' => 'datetime',
        ];
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
