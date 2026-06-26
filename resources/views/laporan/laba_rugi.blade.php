@extends('layouts.app')
@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-chart-pie me-2 text-primary"></i>Laporan Laba Rugi Sementara</h4>
        <p class="text-muted small mb-0">Periode Bulan: <strong>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</strong></p>
    </div>
    <div>
        <a href="{{ route('laporan.laba-rugi.pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm me-1"><i class="fas fa-file-pdf me-1"></i> Export PDF</a>
        <a href="{{ route('laporan.laba-rugi.excel', request()->query()) }}" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i> Export Excel</a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('laporan.laba-rugi') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Pilih Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Pilih Tahun</label>
                <select name="tahun" class="form-select form-select-sm">
                    @for($y=date('Y'); $y>=2023; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">Tampilkan Laporan</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Kolom Pendapatan -->
    <div class="col-lg-6">
        <div class="card h-100 mb-0 border-success border-top border-4">
            <div class="card-header bg-success bg-opacity-10 text-success fw-bold">
                <i class="fas fa-arrow-down me-2"></i>ESTIMASI PENDAPATAN (PRODUKSI JADI)
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 small">
                    <thead class="bg-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-end">Total Produksi</th>
                            <th class="text-end">Harga Estimasi</th>
                            <th class="text-end">Subtotal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendapatanPerProduk as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item['nama_produk'] }}</td>
                            <td class="text-end">{{ number_format($item['total_bersih'], 0, ',', '.') }} {{ $item['satuan'] }}</td>
                            <td class="text-end text-muted">Rp {{ number_format($item['harga_estimasi'], 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">Belum ada hasil produksi terverifikasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white p-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold">TOTAL PENDAPATAN:</span>
                <span class="fs-5 fw-bold text-success font-monospace">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Kolom Pengeluaran -->
    <div class="col-lg-6">
        <div class="card h-100 mb-0 border-danger border-top border-4">
            <div class="card-header bg-danger bg-opacity-10 text-danger fw-bold">
                <i class="fas fa-arrow-up me-2"></i>TOTAL BIAYA OPERASIONAL
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 small">
                    <thead class="bg-light">
                        <tr>
                            <th>Kategori Biaya</th>
                            <th class="text-end">Total Biaya (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluaranPerKategori as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item['nama_kategori'] }}</td>
                            <td class="text-end fw-bold text-danger">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-3 text-muted">Belum ada pengeluaran terverifikasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white p-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold">TOTAL PENGELUARAN:</span>
                <span class="fs-5 fw-bold text-danger font-monospace">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Kesimpulan Laba Rugi -->
<div class="card mb-4 {{ $labaBersih >= 0 ? 'bg-success' : 'bg-danger' }} text-white shadow-lg">
    <div class="card-body p-4 text-center">
        <h6 class="text-uppercase letter-spacing-1 opacity-75 mb-1">KESIMPULAN LABA / RUGI BERSIH SEMENTARA</h6>
        <h1 class="display-5 fw-bold font-monospace mb-1">
            Rp {{ number_format($labaBersih, 0, ',', '.') }}
        </h1>
        <p class="mb-0 small opacity-90">
            @if($labaBersih >= 0)
                <i class="fas fa-face-smile me-1"></i> Usaha mengalami keuntungan (Surplus) pada periode ini.
            @else
                <i class="fas fa-face-frown me-1"></i> Usaha mengalami kerugian (Defisit) pada periode ini.
            @endif
        </p>
    </div>
</div>
@endsection
