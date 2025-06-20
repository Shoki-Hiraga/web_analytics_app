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
    public function index(Request $request)
    {
        $query = GscQshaOh::query();

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->input('end_date'));
        }

        $records = $query->orderBy('start_date', 'desc')->get();

        return view('main.gsc_index', compact('records'));
    }

    public function showByDirectory(Request $request)
    {
        $path = $request->path(); // 例: gsc_qsha_oh/maker
        $directory = '/' . last(explode('/', $path)) . '/'; // /maker/
        $baseUrl = 'https://www.qsha-oh.com';

        $query = GscQshaOh::where('page_url', $baseUrl . $directory);

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->input('end_date'));
        }

        $records = $query->orderBy('start_date', 'desc')->get();

        return view('main.gsc_index', compact('records', 'directory'));
    }
}
