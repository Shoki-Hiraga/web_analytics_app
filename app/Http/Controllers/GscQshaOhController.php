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

        $records = $query->orderBy('start_date', 'asc')->get();

        // チャートデータをページURLごとに月単位でまとめる
        $chartDataByUrl = $records->groupBy('page_url')->map(function ($groupedByUrl) {
            return $groupedByUrl->groupBy(function ($item) {
                return $item->start_date->format('Y-m');
            })->map(function ($items, $month) {
                $impressions = $items->sum('total_impressions');
                $clicks = $items->sum('total_clicks');
                $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;
                $position = $items->avg('avg_position');

                return [
                    'month' => $month,
                    'impressions' => $impressions,
                    'clicks' => $clicks,
                    'ctr' => $ctr,
                    'position' => round($position, 2),
                ];
            })->values();
        });

        return view('main.gsc_index', compact('records', 'chartDataByUrl'));
    }


    public function showByDirectory(Request $request)
    {
        $path = $request->path();
        $directory = '/' . last(explode('/', $path)) . '/';
        $baseUrl = 'https://www.qsha-oh.com';
        $fullUrl = $baseUrl . $directory;

        $query = GscQshaOh::where('page_url', $fullUrl);

        if ($request->filled('start_month')) {
            $start = Carbon::parse($request->input('start_month'))->startOfMonth();
            $query->where('start_date', '>=', $start);
        }

        if ($request->filled('end_month')) {
            $end = Carbon::parse($request->input('end_month'))->endOfMonth();
            $query->where('start_date', '<=', $end);
        }

        $records = $query->orderBy('start_date', 'asc')->get();

        // ✅ この1行がポイント
        $chartDataByUrl = $records->groupBy('page_url')->map(function ($groupedByUrl) {
            return $groupedByUrl->groupBy(function ($item) {
                return $item->start_date->format('Y-m');
            })->map(function ($items, $month) {
                $impressions = $items->sum('total_impressions');
                $clicks = $items->sum('total_clicks');
                $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;
                $position = $items->avg('avg_position');

                return [
                    'month' => $month,
                    'impressions' => $impressions,
                    'clicks' => $clicks,
                    'ctr' => $ctr,
                    'position' => round($position, 2),
                ];
            })->values();
        });

        return view('main.gsc_index', compact('records', 'directory', 'chartDataByUrl'));
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
