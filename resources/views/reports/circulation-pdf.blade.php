<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Sirkulasi Alat Laboratorium</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #003366;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #003366;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        .stats {
            margin: 20px 0;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 10px;
        }
        .stat-box {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .stat-box h3 {
            margin: 0;
            color: #003366;
            font-size: 16px;
        }
        .stat-box p {
            margin: 5px 0 0 0;
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #003366;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
        .overdue {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>UNIVERSITAS MUHAMMADIYAH SEMARANG</h1>
        <p>LAPORAN SIRKULASI ALAT LABORATORIUM</p>
        <p>Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <h3>{{ $stats['total_loans'] }}</h3>
            <p>Total Peminjaman</p>
        </div>
        <div class="stat-box">
            <h3>{{ $stats['returned'] }}</h3>
            <p>Dikembalikan</p>
        </div>
        <div class="stat-box">
            <h3>{{ $stats['active'] }}</h3>
            <p>Masih Dipinjam</p>
        </div>
        <div class="stat-box">
            <h3>{{ $stats['overdue'] }}</h3>
            <p>Terlambat</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali Est.</th>
                <th>Tgl Kembali Aktual</th>
                <th>Status</th>
                <th>Terlambat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $key => $loan)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $loan->user->name ?? '-' }}</td>
                    <td>{{ $loan->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $loan->started_at ? $loan->started_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $loan->due_date ? $loan->due_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y H:i') : 'Belum' }}</td>
                    <td>{{ ucfirst($loan->status) }}</td>
                    <td @if($loan->isOverdue()) class="overdue" @endif>
                        @if($loan->isOverdue())
                            {{ $loan->getDaysOverdue() }} hari
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dicetak otomatis oleh Sistem SimpleLab pada {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
