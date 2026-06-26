@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-edit me-2 text-primary"></i>Edit Produk: {{ $produk->nama_produk }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('master.produk.index') }}">Produk</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card max-w-lg">
    <div class="card-header">Formulir Perbarui Produk</div>
    <div class="card-body p-4">
        <form action="{{ route('master.produk.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Kode Produk <span class="text-danger">*</span></label>
                    <input type="text" name="kode_produk" class="form-control font-monospace @error('kode_produk') is-invalid @enderror" value="{{ old('kode_produk', $produk->kode_produk) }}" required>
                    @error('kode_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                    @error('nama_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Satuan Ukur <span class="text-danger">*</span></label>
                    <select name="satuan_id" class="form-select @error('satuan_id') is-invalid @enderror" required>
                        @foreach($satuans as $sat)
                            <option value="{{ $sat->id }}" {{ old('satuan_id', $produk->satuan_id) == $sat->id ? 'selected' : '' }}>{{ $sat->nama_satuan }}</option>
                        @endforeach
                    </select>
                    @error('satuan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Harga Estimasi (Rp) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control input-nominal @error('harga_estimasi') is-invalid @enderror" data-target="harga_estimasi_val" value="{{ number_format(old('harga_estimasi', $produk->harga_estimasi),0,',','.') }}" required>
                    <input type="hidden" name="harga_estimasi" id="harga_estimasi_val" value="{{ old('harga_estimasi', $produk->harga_estimasi) }}">
                    @error('harga_estimasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Stok Minimum <span class="text-danger">*</span></label>
                    <input type="number" name="stok_minimum" class="form-control @error('stok_minimum') is-invalid @enderror" value="{{ old('stok_minimum', $produk->stok_minimum) }}" min="0" required>
                    @error('stok_minimum') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $produk->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-semibold" for="is_active">Status Produk Aktif</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Produk</button>
                <a href="{{ route('master.produk.index') }}" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
