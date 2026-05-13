<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Restock - Mantu Ngopi</title>
    <style>
        * {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            box-sizing: border-box;
        }

        body {
            margin: 20px;
            color: #1a1a2e;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
            border-bottom: 2px solid #6c3fc5;
            padding-bottom: 12px;
        }

        .header .brand {
            font-size: 18pt;
            font-weight: bold;
            color: #6c3fc5;
            letter-spacing: 2px;
        }

        .header .title {
            font-size: 11pt;
            font-weight: bold;
            color: #1a1a2e;
            margin-top: 2px;
        }

        .header .meta {
            font-size: 8pt;
            color: #666;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background-color: #6c3fc5;
            color: #ffffff;
        }

        thead th {
            padding: 7px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 8.5pt;
        }

        tbody tr:nth-child(even) {
            background-color: #f4f0fb;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #e8e0f5;
            vertical-align: middle;
        }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        .role-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 7.5pt;
            font-weight: bold;
            background: #e5e7eb;
            color: #374151;
            text-transform: capitalize;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 7.5pt;
            color: #999;
            border-top: 1px solid #e0d9f5;
            padding-top: 8px;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="brand">☕ MANTU NGOPI</div>
        <div class="title">LAPORAN HISTORI RESTOCK - {{ strtoupper($monthName) }}</div>
        <div class="meta">Dicetak pada: {{ $date }} &nbsp;|&nbsp; Total data: {{ $restocks->count() }} entri restock</div>
    </div>

    {{-- Summary --}}
    @php
        $totalBiaya     = $restocks->sum('price');
        $totalQty       = $restocks->sum('qty_added');
        $totalBahanBaku = $restocks->pluck('supply_id')->unique()->count();
        $totalPetugas   = $restocks->pluck('user_id')->unique()->count();
    @endphp

    <table style="margin-bottom:14px; border-collapse:separate; border-spacing:6px 0;">
        <tr>
            <td style="width:25%; background:#f4f0fb; border-left:3px solid #6c3fc5; padding:8px 10px;">
                <div style="font-size:7.5pt; color:#888; text-transform:uppercase; letter-spacing:0.5px;">Total Biaya Restock</div>
                <div style="font-size:11pt; font-weight:bold; color:#6c3fc5; margin-top:2px;">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</div>
            </td>
            <td style="width:25%; background:#d1fae5; border-left:3px solid #059669; padding:8px 10px;">
                <div style="font-size:7.5pt; color:#888; text-transform:uppercase; letter-spacing:0.5px;">Total Qty Ditambah</div>
                <div style="font-size:11pt; font-weight:bold; color:#059669; margin-top:2px;">{{ number_format($totalQty, 0, ',', '.') }}</div>
            </td>
            <td style="width:25%; background:#fef9c3; border-left:3px solid #d97706; padding:8px 10px;">
                <div style="font-size:7.5pt; color:#888; text-transform:uppercase; letter-spacing:0.5px;">Jenis Bahan Baku</div>
                <div style="font-size:11pt; font-weight:bold; color:#d97706; margin-top:2px;">{{ $totalBahanBaku }}</div>
            </td>
            <td style="width:25%; background:#dbeafe; border-left:3px solid #2563eb; padding:8px 10px;">
                <div style="font-size:7.5pt; color:#888; text-transform:uppercase; letter-spacing:0.5px;">Petugas Terlibat</div>
                <div style="font-size:11pt; font-weight:bold; color:#2563eb; margin-top:2px;">{{ $totalPetugas }}</div>
            </td>
        </tr>
    </table>

    @if($restocks->isEmpty())
        <div class="no-data">Tidak ada data restock.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Bahan Baku</th>
                    <th class="text-right">Qty Ditambah</th>
                    <th>Satuan</th>
                    <th class="text-right">Total Harga</th>
                    <th>Dicatat Oleh</th>
                    <th>Role</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($restocks as $i => $restock)
                    <tr>
                        <td class="text-center" style="color:#999;">{{ $i + 1 }}</td>
                        <td style="font-weight:bold;">{{ $restock->supply->name ?? '-' }}</td>
                        <td class="text-right">{{ number_format($restock->qty_added, 0, ',', '.') }}</td>
                        <td style="color:#666;">{{ $restock->supply->unit ?? '-' }}</td>
                        <td class="text-right" style="font-weight:bold;">
                            Rp {{ number_format($restock->price, 0, ',', '.') }}
                        </td>
                        <td>{{ $restock->user->fullname ?? '-' }}</td>
                        <td><span class="role-badge">{{ $restock->user->role ?? '-' }}</span></td>
                        <td>{{ $restock->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Laporan ini digenerate secara otomatis oleh sistem Mantu Ngopi &nbsp;&mdash;&nbsp; {{ $date }}
    </div>

</body>
</html>
