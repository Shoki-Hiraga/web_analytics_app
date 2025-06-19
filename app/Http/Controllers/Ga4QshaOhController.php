<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ga4QshaOh;

class Ga4QshaOhController extends Controller
{
    /**
     * データの一覧を表示
     */
    public function index()
    {
        $records = Ga4QshaOh::orderBy('start_date', 'desc')->get();
        return view('main.ga4_index', compact('records'));
    }
}
