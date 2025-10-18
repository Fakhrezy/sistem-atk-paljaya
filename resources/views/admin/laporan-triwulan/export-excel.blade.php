<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Triwulan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .number {
            mso-number-format: "#,##0";
        }

        .currency {
            mso-number-format: "_-\"Rp \"* #,##0_-;-\"Rp \"* #,##0_-;_-\"Rp \"* \"-\"_-;_-@_-";
        }
    </style>
</head>

<body>
    <h2>Laporan Triwulan ATK</h2>
    <p>Tanggal Export: {{ date('d/m/Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Barang</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Tahun</th>
                <th class="text-center">Triwulan</th>
                <th class="text-center">Saldo Akhir Sebelumnya</th>
                <th class="text-center">Jumlah Pengadaan</th>
                <th class="text-center">Harga Satuan</th>
                <th class="text-center">Jumlah Harga</th>
                <th class="text-center">Jumlah Pemakaian</th>
                <th class="text-center">Saldo Tersedia</th>
                <th class="text-center">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $laporan->nama_barang }}</td>
                <td class="text-center">{{ $laporan->satuan }}</td>
                <td class="text-center">{{ $laporan->tahun }}</td>
                <td class="text-center">Q{{ $laporan->triwulan }}</td>
                <td class="text-center number">{{ $laporan->saldo_akhir_sebelumnya }}</td>
                <td class="text-center number">{{ $laporan->jumlah_pengadaan }}</td>
                <td class="text-right currency">{{ $laporan->harga_satuan }}</td>
                <td class="text-right currency">{{ $laporan->jumlah_harga }}</td>
                <td class="text-center number">{{ $laporan->jumlah_pemakaian }}</td>
                <td class="text-center number">{{ $laporan->saldo_tersedia }}</td>
                <td class="text-right currency">{{ $laporan->total_harga }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="5" class="text-center">TOTAL</td>
                <td class="text-center number">{{ $laporans->sum('saldo_akhir_sebelumnya') }}</td>
                <td class="text-center number">{{ $laporans->sum('jumlah_pengadaan') }}</td>
                <td></td>
                <td class="text-right currency">{{ $laporans->sum('jumlah_harga') }}</td>
                <td class="text-center number">{{ $laporans->sum('jumlah_pemakaian') }}</td>
                <td class="text-center number">{{ $laporans->sum('saldo_tersedia') }}</td>
                <td class="text-right currency">{{ $laporans->sum('total_harga') }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>