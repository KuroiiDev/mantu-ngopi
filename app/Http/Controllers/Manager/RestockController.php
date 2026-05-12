<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;

use App\Models\Restock;
use App\Models\Supply;
use Illuminate\Http\Request;

class RestockController extends Controller
{
    public function index()
    {
        $restocks = Restock::with(['supply', 'user'])->latest()->get();
        return view('manager.restocks.index', compact('restocks'));
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
