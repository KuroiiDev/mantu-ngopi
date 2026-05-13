<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - Mantu Ngopi</title>
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

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }

        .summary-box {
            display: table-cell;
            width: 25%;
            padding: 8px 10px;
            background-color: #f4f0fb;
            border-left: 3px solid #6c3fc5;
            vertical-align: top;
        }

        .summary-box+.summary-box {
            margin-left: 8px;
        }

        .summary-box .label {
            font-size: 7.5pt;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-box .value {
            font-size: 11pt;
            font-weight: bold;
            color: #6c3fc5;
            margin-top: 2px;
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

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 7.5pt;
            font-weight: bold;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-completed {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-default {
            background: #e5e7eb;
            color: #374151;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
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
        <div class="title">LAPORAN HISTORI TRANSAKSI - {{ strtoupper($monthName) }}</div>
        <div class="meta">Dicetak pada: {{ $date }} &nbsp;|&nbsp; Total data: {{ $transactions->count() }} transaksi
        </div>
    </div>

    {{-- Summary boxes using table layout for dompdf compatibility --}}
    @php
        $totalPendapatan = $transactions->where('status', 'paid')->sum('total') + $transactions->where('status', 'completed')->sum('total');
        $totalPaid = $transactions->where('status', 'paid')->count();
        $totalPending = $transactions->where('status', 'pending')->count();
        $totalCancelled = $transactions->where('status', 'cancelled')->count();
    @endphp

    <table style="margin-bottom:14px; border-collapse:separate; border-spacing:6px 0;">
        <tr>
            <td style="width:25%; background:#f4f0fb; border-left:3px solid #6c3fc5; padding:8px 10px;">
                <div style="font-size:7.5pt; color:#888; text-transform:uppercase; letter-spacing:0.5px;">Total
                    Pendapatan</div>
                <div style="font-size:11pt; font-weight:bold; color:#6c3fc5; margin-top:2px;">Rp
                    {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </td>
            <td style="width:25%; background:#d1fae5; border-left:3px solid #059669; padding:8px 10px;">
                <div style="font-size:7.5pt; color:#888; text-transform:uppercase; letter-spacing:0.5px;">Transaksi Paid
                </div>
                <div style="font-size:11pt; font-weight:bold; color:#059669; margin-top:2px;">{{ $totalPaid }}</div>
            </td>
            <td style="width:25%; background:#fef9c3; border-left:3px solid #d97706; padding:8px 10px;">
                <div style="font-size:7.5pt; color:#888; text-transform:uppercase; letter-spacing:0.5px;">Transaksi
                    Pending</div>
                <div style="font-size:11pt; font-weight:bold; color:#d97706; margin-top:2px;">{{ $totalPending }}</div>
            </td>
            <td style="width:25%; background:#fee2e2; border-left:3px solid #dc2626; padding:8px 10px;">
                <div style="font-size:7.5pt; color:#888; text-transform:uppercase; letter-spacing:0.5px;">Transaksi
                    Batal</div>
                <div style="font-size:11pt; font-weight:bold; color:#dc2626; margin-top:2px;">{{ $totalCancelled }}
                </div>
            </td>
        </tr>
    </table>

    @if($transactions->isEmpty())
        <div class="no-data">Tidak ada data transaksi.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>Kasir</th>
                    <th>Item</th>
                    <th class="text-right">Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $i => $transaction)
                    @php
                        $badgeClass = match ($transaction->status) {
                            'paid' => 'badge-paid',
                            'pending' => 'badge-pending',
                            'cancelled' => 'badge-cancelled',
                            'completed' => 'badge-completed',
                            default => 'badge-default',
                        };
                    @endphp
                    <tr>
                        <td class="text-center" style="color:#999;">{{ $i + 1 }}</td>
                        <td>{{ $transaction->customer ?? '-' }}</td>
                        <td>{{ $transaction->user->fullname ?? '-' }}</td>
                        <td>
                            @foreach($transaction->products as $product)
                                {{ $product->name }} ({{ $product->pivot->qty }})@if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td class="text-right" style="font-weight:bold;">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </td>
                        <td style="text-transform:uppercase;">{{ $transaction->method ?? '-' }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ $transaction->status }}</span></td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
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