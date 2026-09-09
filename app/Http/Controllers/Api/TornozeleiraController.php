<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\tbtornozeleira;

class TornozeleiraController extends Controller
{
    public function index()
    {
        $tornozeleiras = tbtornozeleira::all();

        return response()->json($tornozeleiras);
    }

    public function show($id)
    {
        $tornozeleira = tbtornozeleira::findOrFail($id);

        return response()->json($tornozeleira);
    }
}