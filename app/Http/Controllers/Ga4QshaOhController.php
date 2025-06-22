<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ga4QshaOh;
use Carbon\Carbon;

class Ga4QshaOhController extends Controller
{
    /**
     * データの一覧を表示
     */
    public function index(Request $request)
    {
        $query = Ga4QshaOh::query();

        if ($request->filled('start_month')) {
            $start = Carbon::parse($request->input('start_month'))->startOfMonth();
            $query->where('start_date', '>=', $start);
        }

        if ($request->filled('end_month')) {
            $end = Carbon::parse($request->input('end_month'))->endOfMonth();
            $query->where('start_date', '<=', $end);
        }

        $records = $query->orderBy('start_date', 'asc')->get();

        // チャート用データ（landing_urlごとにまとめる）
        $chartDataByUrl = $records->groupBy('landing_url')->map(function ($groupedByUrl) {
            return $groupedByUrl->groupBy(function ($item) {
                return $item->start_date->format('Y-m');
            })->map(function ($items, $month) {
                return [
                    'month' => $month,
                    'total_sessions' => $items->sum('total_sessions'),
                    'cv_count' => $items->sum('cv_count'),
                ];
            })->values();
        });

        return view('main.ga4_index', compact('records', 'chartDataByUrl'));
    }

    /**
     * ランディングURLごとのデータ表示
     */
    public function showByDirectory(Request $request)
    {
        $path = $request->path();
        $directory = '/' . last(explode('/', $path)) . '/';

        $query = Ga4QshaOh::where('landing_url', $directory);

        if ($request->filled('start_month')) {
            $start = Carbon::parse($request->input('start_month'))->startOfMonth();
            $query->where('start_date', '>=', $start);
        }

        if ($request->filled('end_month')) {
            $end = Carbon::parse($request->input('end_month'))->endOfMonth();
            $query->where('start_date', '<=', $end);
        }

        $records = $query->orderBy('start_date', 'asc')->get();

        // chartDataByUrl を1件分でも生成する（同じ構造）
        $chartDataByUrl = collect([$directory => $records->groupBy(function ($item) {
            return $item->start_date->format('Y-m');
        })->map(function ($items, $month) {
            return [
                'month' => $month,
                'total_sessions' => $items->sum('total_sessions'),
                'cv_count' => $items->sum('cv_count'),
            ];
        })->values()]);

        return view('main.ga4_index', compact('records', 'directory', 'chartDataByUrl'));
    }

    /**
     * 前年同月比
     */
    public function yoy(Request $request)
    {
        $baseDate = Carbon::parse($request->input('date', now()));
        $thisYear = $this->getRecords($baseDate->copy()->startOfMonth(), $baseDate->copy()->endOfMonth());
        $lastYear = $this->getRecords(
            $baseDate->copy()->subYear()->startOfMonth(),
            $baseDate->copy()->subYear()->endOfMonth()
        );

        return view('main.ga4_yoy', compact('thisYear', 'lastYear', 'baseDate'));
    }

    /**
     * 前月比
     */
    public function mom(Request $request)
    {
        $baseDate = Carbon::parse($request->input('date', now()));
        $thisMonth = $this->getRecords($baseDate->copy()->startOfMonth(), $baseDate->copy()->endOfMonth());
        $lastMonth = $this->getRecords(
            $baseDate->copy()->subMonth()->startOfMonth(),
            $baseDate->copy()->subMonth()->endOfMonth()
        );

        return view('main.ga4_mom', compact('thisMonth', 'lastMonth', 'baseDate'));
    }

    /**
     * 共通の集計処理
     */
    private function getRecords($start, $end)
    {
        return Ga4QshaOh::whereBetween('start_date', [$start, $end])
            ->orderBy('start_date', 'desc')
            ->get();
    }

}
