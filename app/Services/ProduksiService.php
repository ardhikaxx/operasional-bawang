<?php

namespace App\Services;

use App\Models\Produksi;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProduksiService
{
    public function __construct(
        protected StokService $stokService,
        protected AuditLogService $auditLog
    ) {}

    public function generateKodeProduksi(string $tanggal): string
    {
        $dateStr = date('Ymd', strtotime($tanggal));
        $prefix  = "PRD-{$dateStr}-";
        
        $last = Produksi::where('kode_produksi', 'like', "{$prefix}%")
            ->orderBy('kode_produksi', 'desc')
            ->first();

        if ($last) {
            $num = (int) substr($last->kode_produksi, -3) + 1;
        } else {
            $num = 1;
        }

        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function simpan(array $data): Produksi
    {
        $produk = Produk::findOrFail($data['produk_id']);
        
        $data['kode_produksi'] = $this->generateKodeProduksi($data['tanggal_produksi']);
        $data['satuan_id']     = $produk->satuan_id;
        $data['karyawan_id']   = $data['karyawan_id'] ?? Auth::id();
        $data['status']        = 'draft';

        $produksi = Produksi::create($data);
        
        $jumlahBersih = $produksi->jumlah_produksi - $produksi->jumlah_gagal;
        $this->stokService->tambahStok($produksi->produk_id, $jumlahBersih, 'Produksi', $produksi->id, Auth::id(), "Input Produksi {$produksi->kode_produksi}");
        $this->auditLog->catat('Produksi', 'create', "Input produksi {$produksi->kode_produksi}", null, $produksi->toArray());
        
        return $produksi;
    }

    public function update(Produksi $produksi, array $data): Produksi
    {
        if ($produksi->status === 'terverifikasi' && Auth::user()->role !== 'owner') {
            throw new Exception('Data produksi yang telah diverifikasi tidak dapat diubah.');
        }

        $lama = $produksi->toArray();
        $produk = Produk::findOrFail($data['produk_id']);

        // Rollback stok lama
        $jumlahBersihLama = $produksi->jumlah_produksi - $produksi->jumlah_gagal;
        $this->stokService->kurangiStok($produksi->produk_id, $jumlahBersihLama, 'Produksi', $produksi->id, Auth::id(), "Rollback update produksi {$produksi->kode_produksi}");

        // Update data
        $data['satuan_id'] = $produk->satuan_id;
        $produksi->update($data);

        // Tambah stok baru
        $jumlahBersihBaru = $produksi->jumlah_produksi - $produksi->jumlah_gagal;
        $this->stokService->tambahStok($produksi->produk_id, $jumlahBersihBaru, 'Produksi', $produksi->id, Auth::id(), "Update produksi {$produksi->kode_produksi}");
        
        $this->auditLog->catat('Produksi', 'update', "Update produksi {$produksi->kode_produksi}", $lama, $produksi->fresh()->toArray());
        
        return $produksi;
    }

    public function verifikasi(Produksi $produksi): Produksi
    {
        $lama = $produksi->toArray();
        $produksi->update([
            'status'      => 'terverifikasi',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);
        
        $this->auditLog->catat('Produksi', 'verify', "Verifikasi produksi {$produksi->kode_produksi}", $lama, $produksi->fresh()->toArray());
        
        return $produksi;
    }

    public function hapus(Produksi $produksi): void
    {
        $lama = $produksi->toArray();
        $jumlahBersih = $produksi->jumlah_produksi - $produksi->jumlah_gagal;
        
        // Rollback stok
        $this->stokService->kurangiStok($produksi->produk_id, $jumlahBersih, 'Produksi', $produksi->id, Auth::id(), "Hapus produksi {$produksi->kode_produksi}");
        
        $kode = $produksi->kode_produksi;
        $produksi->delete();
        
        $this->auditLog->catat('Produksi', 'delete', "Hapus produksi {$kode}", $lama, null);
    }
}
