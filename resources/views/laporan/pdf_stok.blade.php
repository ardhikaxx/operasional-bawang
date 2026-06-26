<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Gudang UD. SBT</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1B6B3A; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1B6B3A; font-size: 18px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th { background: #1B6B3A; color: #fff; padding: 6px 8px; font-size: 11px; text-align: left; }
        .table td { padding: 6px 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
        .text-right { text-align: right; }
        .text-danger { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UD. SUMBER BAWANG TIMUR</h1>
        <p>Laporan Posisi Stok Gudang (Fisik Aktual)</p>
        <p>Per Tanggal: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Satuan</th>
                <th class="text-right">Stok Tersedia</th>
                <th class="text-right">Batas Min</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->produk->kode_produk ?? '-' }}</td>
                <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                <td>{{ $item->produk->satuan->nama_satuan ?? '-' }}</td>
                <td class="text-right {{ $item->jumlah_stok <= ($item->produk->stok_minimum ?? 0) ? 'text-danger' : '' }}">
                    <strong>{{ number_format($item->jumlah_stok, 0, ',', '.') }}</strong>
                </td>
                <td class="text-right">{{ number_format($item->produk->stok_minimum ?? 0, 0, ',', '.') }}</td>
                <td>{{ $item->jumlah_stok <= ($item->produk->stok_minimum ?? 0) ? 'KRITIS' : 'AMAN' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
