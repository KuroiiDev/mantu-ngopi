<!DOCTYPE html>
<html>

<head>
    <style>
        * {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="header text-center">
        <strong>MANTU NGOPI</strong><br>
        Kasir: {{ $transaction->user->name }}<br>
        Pelanggan: {{ $transaction->customer ?? 'Guest' }}<br>
        {{ $date }}
    </div>

    <div class="line"></div>

    <table>
        @foreach($transaction->products as $product)
            <tr>
                <td colspan="2">{{ $product->name }}</td>
            </tr>
            <tr>
                <td>
                    {{ $product->pivot->qty }} x {{ number_format($product->pivot->price_at_transaction, 0, ',', '.') }}
                </td>
                <td style="text-align: right;">
                    {{ number_format($product->pivot->qty * $product->pivot->price_at_transaction, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>TOTAL</td>
            <td class="text-right"><strong>{{ number_format($transaction->total) }}</strong></td>
        </tr>
        <tr>
            <td>METODE</td>
            <td class="text-right">{{ strtoupper($transaction->method) }}</td>
        </tr>
        <tr>
            <td>BAYAR</td>
            <td class="text-right">
                {{ number_format($transaction->paid, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>KEMBALI</td>
            <td style="text-align: right;">
                {{ number_format($transaction->paid - $transaction->total, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="line"></div>
    <div class="text-center" style="margin-top: 10px;">
        Terima Kasih Atas Kunjungannya!
    </div>
</body>

</html>