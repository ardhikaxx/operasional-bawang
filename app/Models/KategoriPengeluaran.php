<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model
{
    protected $fillable = ['nama_kategori', 'deskripsi', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }
}
