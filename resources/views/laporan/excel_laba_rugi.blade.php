<table border="1">
    <thead>
        <tr>
            <th colspan="4" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN LABA RUGI — UD. SUMBER BAWANG TIMUR</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">Periode: {{ $bulan }}/{{ $tahun }} | Dicetak: {{ date('d/m/Y H:i') }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="3" style="font-weight: bold; background-color: #f8f9fa;">LABA BERSIH:</th>
            <th style="font-weight: bold; background-color: #f8f9fa;">{{ $labaBersih }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="4" style="font-weight: bold; background-color: #198754; color: #ffffff;">1. ESTIMASI PENDAPATAN PRODUKSI</th>
        </tr>
        <tr style="background-color: #198754; color: #ffffff;">
            <th>Produk</th>
            <th>Total Bersih</th>
            <th>Harga Estimasi</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendapatanPerProduk as $item)
        <tr>
            <td>{{ $item['nama_produk'] }}</td>
            <td>{{ $item['total_bersih'] }} {{ $item['satuan'] }}</td>
            <td>{{ $item['harga_estimasi'] }}</td>
            <td>{{ $item['subtotal'] ?? $item['total'] ?? 0 }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" style="font-weight: bold; text-align: right;">TOTAL PENDAPATAN:</td>
            <td style="font-weight: bold;">{{ $totalPendapatan }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="4" style="font-weight: bold; background-color: #DC3545; color: #ffffff;">2. BIAYA OPERASIONAL</th>
        </tr>
        <tr style="background-color: #DC3545; color: #ffffff;">
            <th colspan="3">Kategori Biaya</th>
            <th>Total Biaya</th>
        </tr>
        @foreach($pengeluaranPerKategori as $item)
        <tr>
            <td colspan="3">{{ $item['nama_kategori'] }}</td>
            <td>{{ $item['subtotal'] ?? $item['total'] ?? 0 }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" style="font-weight: bold; text-align: right;">TOTAL PENGELUARAN:</td>
            <td style="font-weight: bold;">{{ $totalPengeluaran }}</td>
        </tr>
    </tbody>
</table>
