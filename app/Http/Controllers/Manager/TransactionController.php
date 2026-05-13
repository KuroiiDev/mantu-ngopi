<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'products']);

        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month);
            $query->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year);
        }

        $transactions = $query->latest()->get();
        return view('manager.transactions.index', compact('transactions'));
    }

    public function export(Request $request)
    {
        $query = Transaction::with(['user', 'products']);

        if ($request->filled('month')) {
            $targetDate = \Carbon\Carbon::parse($request->month);
            $monthName = $targetDate->translatedFormat('F Y');
            $query->whereMonth('created_at', $targetDate->month)
                ->whereYear('created_at', $targetDate->year);
            $filename = 'laporan_transaksi_' . $targetDate->format('Ym') . '.pdf';
        } else {
            $monthName = 'SEMUA WAKTU';
            $filename = 'laporan_transaksi_alltime.pdf';
        }

        $transactions = $query->latest()->get();
        $date = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('manager.transactions.export', compact('transactions', 'date', 'monthName'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'products']);
        return view('manager.transactions.show', compact('transaction'));
    }
}
