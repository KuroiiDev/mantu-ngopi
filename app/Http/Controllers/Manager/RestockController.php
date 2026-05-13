<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;

use App\Models\Restock;
use App\Models\Supply;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RestockController extends Controller
{
    public function index(Request $request)
    {
        $query = Restock::with(['supply', 'user']);

        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month);
            $query->whereMonth('created_at', $date->month)
                  ->whereYear('created_at', $date->year);
        }

        $restocks = $query->latest()->get();
        return view('manager.restocks.index', compact('restocks'));
    }

    public function export(Request $request)
    {
        $query = Restock::with(['supply', 'user']);
        
        if ($request->filled('month')) {
            $targetDate = \Carbon\Carbon::parse($request->month);
            $monthName = $targetDate->translatedFormat('F Y');
            $query->whereMonth('created_at', $targetDate->month)
                  ->whereYear('created_at', $targetDate->year);
            $filename = 'laporan_restock_' . $targetDate->format('Ym') . '.pdf';
        } else {
            $monthName = 'SEMUA WAKTU';
            $filename = 'laporan_restock_alltime.pdf';
        }

        $restocks = $query->latest()->get();
        $date = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('manager.restocks.export', compact('restocks', 'date', 'monthName'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function show(Restock $restock)
    {
        $restock->load(['supply', 'user']);
        return view('manager.restocks.show', compact('restock'));
    }

    public function store(Request $request)
    { /* nanti */
    }
    public function destroy(Restock $restock)
    { /* nanti */
    }
}
