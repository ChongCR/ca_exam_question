<?php

namespace App\Http\Controllers;

use App\Models\Fund;

class FundController extends Controller
{
    public function index()
    {
        $funds = Fund::orderBy('created_at', 'asc')->get();

        return view('fund.index', compact('funds'));
    }
}
