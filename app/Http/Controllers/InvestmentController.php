<?php

namespace App\Http\Controllers;

use App\Models\Investment;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::with(['fund', 'investor'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('investment.index', compact('investments'));
    }
}
