<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbTornozeleira;

class TornozeleiraController extends Controller
{
    public function index()
    {
        $tornozeleiras = TbTornozeleira::all();
        return response()->json($tornozeleiras);
    }

    public function show($id)
    {
        $tornozeleira = TbTornozeleira::findOrFail($id);
        return response()->json($tornozeleira);
    }
}