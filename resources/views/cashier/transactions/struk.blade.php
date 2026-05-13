<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 0;
        }

        * {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.4;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            width: 190.77pt;
            background-color: #fff;
        }

        .wrapper {
            padding: 8mm 6mm;
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .brand {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .address {
            font-size: 8pt;
            margin-bottom: 8px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .info-table td {
            font-size: 8pt;
        }

        .items-table td {
            padding: 3px 0;
        }

        .footer {
            margin-top: 20px;
            font-size: 8pt;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="text-center">
            <div class="brand">☕ MANTU NGOPI</div>
            <div class="address">
                Jl. Ambatukam No. 67<br>
                Telp: 0877-1977-1512
            </div>
        </div>

        <table class="info-table">
            <tr>
                <td style="width: 55%;">No: #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="text-right" style="width: 45%;">{{ $date }}</td>
            </tr>
            <tr>
                <td>Kasir: {{ explode(' ', $transaction->user->fullname ?? $transaction->user->name)[0] }}</td>
                <td class="text-right">{{ $transaction->customer ?? 'Guest' }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <table class="items-table">
            @foreach($transaction->products as $product)
                <tr>
                    <td colspan="2" class="bold">{{ $product->name }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; width: 65%;">
                        {{ $product->pivot->qty }} x {{ number_format($product->pivot->price_at_transaction, 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="width: 35%;">
                        {{ number_format($product->pivot->qty * $product->pivot->price_at_transaction, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="line"></div>

        <table class="total-table">
            <tr>
                <td class="bold" style="width: 40%;">TOTAL</td>
                <td class="text-right bold" style="font-size: 11pt; width: 60%;">Rp
                    {{ number_format($transaction->total, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Metode</td>
                <td class="text-right">{{ strtoupper($transaction->method ?? 'TUNAI') }}</td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td class="text-right">{{ number_format($transaction->paid, 0, ',', '.') }}</td>
            </tr>
            <tr class="bold">
                <td>Kembali</td>
                <td class="text-right">{{ number_format($transaction->paid - $transaction->total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <div class="text-center footer">
            Terima kasih atas kunjungannya!<br>
            <strong>Selamat menikmati kopi Anda</strong>
        </div>
    </div>
</body>

</html>