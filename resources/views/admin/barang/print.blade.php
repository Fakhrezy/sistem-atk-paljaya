<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - Print</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding: 1rem;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            font-size: 1rem;
            color: #4B5563;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #D1D5DB;
            padding: 0.75rem;
            text-align: left;
        }
        th {
            background-color: #F3F4F6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        td {
            font-size: 0.875rem;
        }
        .print-info {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: #6B7280;
        }
        @media print {
            body {
                padding: 0;
            }
            .print-info {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Data Barang</h1>
        <p class="subtitle">Sistem Inventori ATK Paljaya</p>
        @if(request('jenis'))
            <p class="subtitle">Jenis: {{ ucfirst(request('jenis')) }}</p>
        @endif
        @if(request('search'))
            <p class="subtitle">Pencarian: "{{ request('search') }}"</p>
        @endif
        <p class="subtitle">{{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Jenis</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barang as $item)
            <tr>
                <td>{{ $item->id_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->satuan }}</td>
                <td>Rp {{ number_format($item->harga_barang, 0, ',', '.') }}</td>
                <td>{{ $item->stok }}</td>
                <td>{{ ucfirst($item->jenis) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem;">
                    Tidak ada data barang
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="print-info">
        * Dokumen ini dicetak dari Sistem Inventori ATK Paljaya
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
