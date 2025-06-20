<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ga4QshaOh;

class Ga4QshaOhController extends Controller
{
    /**
     * データの一覧を表示
     */
    public function index(Request $request)
    {
        $query = Ga4QshaOh::query();

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->input('end_date'));
        }

        $records = $query->orderBy('start_date', 'desc')->get();

        return view('main.ga4_index', compact('records'));
    }

    public function showByDirectory(Request $request)
    {
        $path = $request->path();
        $directory = '/' . last(explode('/', $path)) . '/';

        $query = Ga4QshaOh::where('landing_url', $directory);

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->input('end_date'));
        }

        $records = $query->orderBy('start_date', 'desc')->get();

        return view('main.ga4_index', compact('records', 'directory'));
    }


}
