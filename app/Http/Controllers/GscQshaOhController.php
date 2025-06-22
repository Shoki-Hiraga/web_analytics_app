<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GscQshaOh;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GscQshaOhController extends Controller
{
    /**
     * GSCデータの一覧を表示
     */
    public function index(Request $request)
    {
        $query = GscQshaOh::query();

        if ($request->filled('start_month')) {
            $start = Carbon::parse($request->input('start_month'))->startOfMonth();
            $query->where('start_date', '>=', $start);
        }

        if ($request->filled('end_month')) {
            $end = Carbon::parse($request->input('end_month'))->endOfMonth();
            $query->where('start_date', '<=', $end);
        }

        $records = $query->orderBy('start_date', 'desc')->get();

        return view('main.gsc_index', compact('records'));
    }

    public function showByDirectory(Request $request)
    {
        $path = $request->path();
        $directory = '/' . last(explode('/', $path)) . '/';
        $baseUrl = 'https://www.qsha-oh.com';

        $query = GscQshaOh::where('page_url', $baseUrl . $directory);

        if ($request->filled('start_month')) {
            $start = Carbon::parse($request->input('start_month'))->startOfMonth();
            $query->where('start_date', '>=', $start);
        }

        if ($request->filled('end_month')) {
            $end = Carbon::parse($request->input('end_month'))->endOfMonth();
            $query->where('start_date', '<=', $end);
        }

        $records = $query->orderBy('start_date', 'desc')->get();

        return view('main.gsc_index', compact('records', 'directory'));
    }

    public function yoy(Request $request)
    {
        $baseDate = Carbon::parse($request->input('date', now()));
        $thisYear = $this->getRecords($baseDate->copy()->startOfMonth(), $baseDate->copy()->endOfMonth());
        $lastYear = $this->getRecords(
            $baseDate->copy()->subYear()->startOfMonth(),
            $baseDate->copy()->subYear()->endOfMonth()
        );

        return view('main.gsc_yoy', compact('thisYear', 'lastYear', 'baseDate'));
    }

    public function mom(Request $request)
    {
        $baseDate = Carbon::parse($request->input('date', now()));
        $thisMonth = $this->getRecords($baseDate->copy()->startOfMonth(), $baseDate->copy()->endOfMonth());
        $lastMonth = $this->getRecords(
            $baseDate->copy()->subMonth()->startOfMonth(),
            $baseDate->copy()->subMonth()->endOfMonth()
        );

        return view('main.gsc_mom', compact('thisMonth', 'lastMonth', 'baseDate'));
    }

    private function getRecords($start, $end)
    {
        return GscQshaOh::whereBetween('start_date', [$start, $end])
            ->orderBy('start_date', 'desc')
            ->get();
    }

}
