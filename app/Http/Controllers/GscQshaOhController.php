<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GscQshaOh;
use Illuminate\Support\Str;

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

    public function showByDirectory(Request $request)
    {
        $path = $request->path(); // gsc_qsha_oh/maker
        $directory = '/' . last(explode('/', $path)) . '/'; // /maker/

        $baseUrl = 'https://www.qsha-oh.com';

        $records = GscQshaOh::where('page_url', $baseUrl . $directory)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('main.gsc_index', compact('records', 'directory'));
    }

}
