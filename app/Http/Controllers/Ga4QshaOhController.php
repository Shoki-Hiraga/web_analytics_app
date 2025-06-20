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

    public function showByDirectory(Request $request)
    {
        $path = $request->path(); // 例: ga4_qsha_oh/maker
        $directory = '/' . last(explode('/', $path)) . '/'; // 例: /maker/

        // データ取得
        $records = Ga4QshaOh::where('landing_url', $directory)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('main.ga4_index', compact('records', 'directory'));
    }

}
