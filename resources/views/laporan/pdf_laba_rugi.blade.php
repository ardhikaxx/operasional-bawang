<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi UD. SBT</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1B6B3A; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1B6B3A; font-size: 18px; }
        .summary { margin-bottom: 15px; padding: 12px; background: #f8f9fa; border: 1px solid #ddd; font-size: 14px; text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        .table th { background: #1B6B3A; color: #fff; padding: 6px 8px; font-size: 11px; text-align: left; }
        .table.danger th { background: #dc3545; }
        .table td { padding: 6px 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
        .text-right { text-align: right; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; }
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UD. SUMBER BAWANG TIMUR</h1>
        <p>Laporan Laba Rugi Periode: {{ $bulan }}/{{ $tahun }}</p>
        <p>Dicetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <strong>LABA BERSIH:</strong> 
        <span style="font-size: 16px; font-weight: bold; color: {{ $labaBersih >= 0 ? '#198754' : '#dc3545' }};">
            Rp {{ number_format($labaBersih, 0, ',', '.') }}
        </span>
    </div>

    <div class="section-title text-success">1. ESTIMASI PENDAPATAN PRODUKSI</div>
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-right">Total Produksi</th>
                <th class="text-right">Harga Estimasi</th>
                <th class="text-right">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendapatanPerProduk as $item)
            <tr>
                <td>{{ $item['nama_produk'] }}</td>
                <td class="text-right">{{ number_format($item['total_bersih'], 0, ',', '.') }} {{ $item['satuan'] }}</td>
                <td class="text-right">Rp {{ number_format($item['harga_estimasi'], 0, ',', '.') }}</td>
                <td class="text-right fw-bold">Rp {{ number_format($item['subtotal'] ?? $item['total'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Belum ada hasil produksi.</td>
            </tr>
            @endforelse
            <tr>
                <td colspan="3"><strong>TOTAL PENDAPATAN</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="section-title text-danger">2. BIAYA OPERASIONAL</div>
    <table class="table danger">
        <thead>
            <tr>
                <th>Kategori Biaya</th>
                <th class="text-right">Total Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluaranPerKategori as $item)
            <tr>
                <td>{{ $item['nama_kategori'] }}</td>
                <td class="text-right">Rp {{ number_format($item['subtotal'] ?? $item['total'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="text-align: center;">Belum ada pengeluaran.</td>
            </tr>
            @endforelse
            <tr>
                <td><strong>TOTAL PENGELUARAN</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
