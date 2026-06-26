@extends('layouts.app')
@section('title', 'Dashboard Karyawan')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-hard-hat me-2 text-primary"></i>Dashboard Operasional</h4>
        <p class="text-muted small mb-0">Selamat bekerja, <strong>{{ auth()->user()->name }}</strong>. Catat aktivitas produksi dan pengeluaran harian Anda.</p>
    </div>
    <div>
        <a href="{{ route('produksi.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Input Produksi Hari Ini</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card stat-card h-100 mb-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Produksi Saya Hari Ini</p>
                        <h2 class="fw-bold mb-0 text-primary">{{ number_format($produksiHariIni, 0, ',', '.') }} <small class="fs-6 text-muted">unit</small></h2>
                        <small class="text-success"><i class="fas fa-calendar-day me-1"></i> {{ date('d F Y') }}</small>
                    </div>
                    <div class="stat-icon bg-primary-light text-primary p-3 rounded-circle">
                        <i class="fas fa-industry fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card stat-card border-danger h-100 mb-0" style="border-left-color: var(--color-danger) !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Pengeluaran Saya Hari Ini</p>
                        <h2 class="fw-bold mb-0 text-danger">Rp {{ number_format($pengeluaranHariIni, 0, ',', '.') }}</h2>
                        <small class="text-muted"><i class="fas fa-file-invoice-dollar me-1"></i> Klaim operasional</small>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger p-3 rounded-circle">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card mb-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-history me-2 text-primary"></i>Riwayat Produksi Saya</span>
                <a href="{{ route('produksi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Bersih</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksiTerbaru as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_produksi)->format('d/m/Y') }}</td>
                            <td class="col-kode fw-bold text-primary">{{ $item->kode_produksi }}</td>
                            <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                            <td class="col-nominal fw-bold text-success">{{ number_format($item->jumlah_bersih, 0, ',', '.') }}</td>
                            <td>
                                @if($item->status === 'draft')
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @else
                                    <span class="badge bg-success">Terverifikasi</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data input produksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-0">
            <div class="card-header">
                <span><i class="fas fa-boxes-stacked me-2 text-info"></i>Informasi Stok Gudang (Read Only)</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="small">Produk</th>
                            <th class="small text-end">Stok Tersedia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokSeluruhProduk as $stok)
                        <tr>
                            <td class="fw-semibold small">{{ $stok->produk->nama_produk ?? '-' }}</td>
                            <td class="text-end font-monospace fw-bold {{ $stok->jumlah_stok <= ($stok->produk->stok_minimum ?? 0) ? 'text-danger' : 'text-success' }}">
                                {{ number_format($stok->jumlah_stok, 0, ',', '.') }} {{ $stok->produk->satuan->nama_satuan ?? 'unit' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-3 text-muted small">Data stok kosong.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
