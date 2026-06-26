@extends('layouts.app')
@section('title', 'Edit Pengeluaran')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-edit me-2 text-danger"></i>Edit Pengeluaran: {{ $pengeluaran->kode_transaksi }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('pengeluaran.index') }}">Pengeluaran</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card max-w-2xl">
    <div class="card-header">Formulir Perbarui Klaim Biaya</div>
    <div class="card-body p-4">
        <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pengeluaran" class="form-control @error('tanggal_pengeluaran') is-invalid @enderror" value="{{ old('tanggal_pengeluaran', $pengeluaran->tanggal_pengeluaran) }}" required>
                    @error('tanggal_pengeluaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Kategori Biaya <span class="text-danger">*</span></label>
                    <select name="kategori_pengeluaran_id" class="form-select @error('kategori_pengeluaran_id') is-invalid @enderror" required>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_pengeluaran_id', $pengeluaran->kategori_pengeluaran_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori_pengeluaran_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Nominal Biaya (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light fw-bold">Rp</span>
                    <input type="text" class="form-control fs-5 fw-bold text-danger input-nominal @error('jumlah') is-invalid @enderror" data-target="jumlah_val" value="{{ number_format(old('jumlah', $pengeluaran->jumlah),0,',','.') }}" required>
                    <input type="hidden" name="jumlah" id="jumlah_val" value="{{ old('jumlah', $pengeluaran->jumlah) }}">
                </div>
                @error('jumlah') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Keterangan / Keperluan <span class="text-danger">*</span></label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" required>{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold">Ganti Bukti Nota (Opsional)</label>
                @if($pengeluaran->bukti_foto)
                    <div class="mb-2">
                        <a href="{{ asset('storage/'.$pengeluaran->bukti_foto) }}" target="_blank" class="badge bg-info text-dark text-decoration-none p-2"><i class="fas fa-image me-1"></i> Lihat Bukti Saat Ini</a>
                    </div>
                @endif
                <input type="file" name="bukti_foto" class="form-control @error('bukti_foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                <div class="form-text small">Kosongkan jika tidak ingin mengganti gambar nota.</div>
                @error('bukti_foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger px-4 fw-bold"><i class="fas fa-save me-2"></i> Perbarui Transaksi</button>
                <a href="{{ route('pengeluaran.index') }}" class="btn btn-light border px-3">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
