@extends('layouts.app')
@section('title', 'Histori Pergerakan Stok')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-history me-2 text-primary"></i>Histori Pergerakan Stok</h4>
        <p class="text-muted small mb-0">Jejak audit seluruh mutasi stok barang (masuk dari produksi, keluar distribusi, dan koreksi opname).</p>
    </div>
    <div>
        <a href="{{ route('stok.index') }}" class="btn btn-light border btn-sm"><i class="fas fa-arrow-left me-1"></i> Kembali ke Persediaan</a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('stok.histori') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Produk</label>
                <select name="produk_id" class="form-select form-select-sm">
                    <option value="">Semua Produk</option>
                    @foreach($produks as $p)
                        <option value="{{ $p->id }}" {{ request('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
                <a href="{{ route('stok.histori') }}" class="btn btn-light btn-sm border"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Log Mutasi Stok (Immutable Log)</div>
    <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
            <thead>
                <tr>
                    <th>Waktu Mutasi</th>
                    <th>Produk</th>
                    <th>Jenis Mutasi</th>
                    <th>Selisih Unit</th>
                    <th>Sebelum</th>
                    <th>Sesudah</th>
                    <th>Referensi / Keterangan</th>
                    <th>Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $item)
                <tr>
                    <td class="small">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>
                    <td class="fw-semibold">{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td>
                        @if($item->jenis === 'masuk')
                            <span class="badge bg-success"><i class="fas fa-arrow-down me-1"></i> Masuk</span>
                        @elseif($item->jenis === 'keluar')
                            <span class="badge bg-warning text-dark"><i class="fas fa-arrow-up me-1"></i> Keluar</span>
                        @else
                            <span class="badge bg-secondary"><i class="fas fa-scale-balanced me-1"></i> Koreksi</span>
                        @endif
                    </td>
                    <td class="col-nominal fw-bold {{ $item->jenis === 'masuk' ? 'text-success' : ($item->jenis === 'keluar' ? 'text-danger' : 'text-primary') }}">
                        {{ $item->jenis === 'keluar' ? '-' : '+' }}{{ number_format($item->jumlah, 0, ',', '.') }} {{ $item->produk->satuan->nama_satuan ?? '' }}
                    </td>
                    <td class="col-nominal text-muted">{{ number_format($item->stok_sebelum, 0, ',', '.') }}</td>
                    <td class="col-nominal fw-bold text-dark">{{ number_format($item->stok_sesudah, 0, ',', '.') }}</td>
                    <td class="small text-truncate" style="max-width: 250px;">
                        @if($item->referensi_tipe)
                            <strong class="text-primary">{{ $item->referensi_tipe }}</strong> — 
                        @endif
                        {{ $item->keterangan ?? '-' }}
                    </td>
                    <td class="small">{{ $item->user->name ?? 'Sistem' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat mutasi stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($histories->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $histories->links() }}
    </div>
    @endif
</div>
@endsection
