<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GscQshaOh;

class GscQshaOhController extends Controller
{
    /**
     * GSCデータの一覧を表示
     */
    public function index()
    {
        $records = GscQshaOh::orderBy('start_date', 'desc')->get();
        return view('main.gsc_index', compact('records'));
    }
}
