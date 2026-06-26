@extends('layouts.app')
@section('title', 'Edit Produksi')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-edit me-2 text-primary"></i>Edit Produksi: {{ $produksi->kode_produksi }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('produksi.index') }}">Produksi</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card max-w-2xl">
    <div class="card-header">Formulir Perbarui Produksi</div>
    <div class="card-body p-4">
        <form action="{{ route('produksi.update', $produksi->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Tanggal Produksi <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_produksi" class="form-control @error('tanggal_produksi') is-invalid @enderror" value="{{ old('tanggal_produksi', $produksi->tanggal_produksi) }}" required>
                    @error('tanggal_produksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if(auth()->user()->role === 'owner')
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Operator / Karyawan <span class="text-danger">*</span></label>
                    <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror" required>
                        @foreach($karyawans as $kar)
                            <option value="{{ $kar->id }}" {{ old('karyawan_id', $produksi->karyawan_id) == $kar->id ? 'selected' : '' }}>{{ $kar->name }}</option>
                        @endforeach
                    </select>
                    @error('karyawan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @else
                <input type="hidden" name="karyawan_id" value="{{ $produksi->karyawan_id }}">
                @endif
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Produk Jadi <span class="text-danger">*</span></label>
                    <select name="produk_id" id="produk_select" class="form-select @error('produk_id') is-invalid @enderror" required>
                        @foreach($produks as $prd)
                            <option value="{{ $prd->id }}" data-satuan="{{ $prd->satuan->nama_satuan ?? '-' }}" {{ old('produk_id', $produksi->produk_id) == $prd->id ? 'selected' : '' }}>
                                {{ $prd->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                    @error('produk_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Satuan</label>
                    <input type="text" id="satuan_display" class="form-control bg-light font-monospace" value="{{ $produksi->satuan->nama_satuan ?? '-' }}" readonly>
                </div>
            </div>

            <div class="row g-3 mb-4 p-3 bg-light rounded border mx-0">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-primary">Jumlah Produksi <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_produksi" class="form-control fw-bold" value="{{ old('jumlah_produksi', $produksi->jumlah_produksi) }}" min="1" required>
                    @error('jumlah_produksi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-danger">Produk Gagal/Rusak</label>
                    <input type="number" name="jumlah_gagal" class="form-control" value="{{ old('jumlah_gagal', $produksi->jumlah_gagal) }}" min="0">
                    @error('jumlah_gagal') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-success">Jumlah Bersih</label>
                    <input type="text" id="jumlah_bersih_display" class="form-control fw-bold text-success border-success bg-white" value="{{ $produksi->jumlah_bersih }} unit" readonly>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan', $produksi->catatan) }}</textarea>
                @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="fas fa-save me-2"></i> Perbarui Produksi</button>
                <a href="{{ route('produksi.index') }}" class="btn btn-light border px-3">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
