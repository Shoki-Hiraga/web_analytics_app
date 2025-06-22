<!DOCTYPE html>
<html lang="ja">
<head>
    <title>GSC集計データ一覧 | @include('components.sitename')</title>
    @include('components.header')
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body>
    <h1>@include('components.sitename')</h1>

    <h2>GSC 集計データ @isset($directory)（{{ $directory }}）@endisset</h2>

@include('components.yyyy-mm-form')


<h2>ページURL別 チャート</h2>
@php
    use Illuminate\Support\Str;

    $chartEntries = [];
    foreach ($chartDataByUrl ?? [] as $url => $data) {
        $base = md5($url); // 共通の接頭語
        $label = Str::replace('https://www.qsha-oh.com', '', $url);
        $chartEntries[] = [
            'label' => $label,
            'data' => $data,
            'impressionId' => "imp_$base",
            'clickId' => "click_$base",
            'ctrId' => "ctr_$base",
            'positionId' => "pos_$base"
        ];
    }
@endphp

@if (!empty($chartEntries))
    @foreach ($chartEntries as $entry)
        <h3>{{ $entry['label'] }}</h3>

        <p>インプレッション数</p>
        <canvas id="{{ $entry['impressionId'] }}" height="60"></canvas>

        <p>クリック数</p>
        <canvas id="{{ $entry['clickId'] }}" height="60"></canvas>

        <p>CTR（％）</p>
        <canvas id="{{ $entry['ctrId'] }}" height="60"></canvas>

        <p>平均掲載順位</p>
        <canvas id="{{ $entry['positionId'] }}" height="60"></canvas>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartEntries = @json($chartEntries);

        chartEntries.forEach(entry => {
            const months = entry.data.map(item => item.month);
            const impressions = entry.data.map(item => item.impressions);
            const clicks = entry.data.map(item => item.clicks);
            const ctr = entry.data.map(item => item.ctr);
            const position = entry.data.map(item => item.position);

            const createChart = (id, label, data, type = 'bar', color = 'rgba(75, 192, 192, 0.6)') => {
                const ctx = document.getElementById(id)?.getContext('2d');
                if (!ctx) return;
                new Chart(ctx, {
                    type: type,
                    data: {
                        labels: months,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: color,
                            borderColor: color,
                            fill: false,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: label }
                            },
                            x: {
                                title: { display: true, text: '年月' }
                            }
                        }
                    }
                });
            };

            createChart(entry.impressionId, 'インプレッション数', impressions, 'bar', 'rgba(54, 162, 235, 0.6)');
            createChart(entry.clickId, 'クリック数', clicks, 'bar', 'rgba(255, 159, 64, 0.6)');
            createChart(entry.ctrId, 'CTR（％）', ctr, 'line', 'rgba(255, 99, 132, 1)');
            createChart(entry.positionId, '平均掲載順位', position, 'line', 'rgba(153, 102, 255, 0.8)');
        });
    </script>
@endif


    <div class="table-container">
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ページURL</th>
                    <th>インプレッション数</th>
                    <th>クリック数</th>
                    <th>CTR（%）</th>
                    <th>平均掲載順位</th>
                    <th>開始日</th>
                    <th>終了日</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr>
                        <td>{{ Str::replace('https://www.qsha-oh.com', '', $record->page_url) }}</td>
                        <td>{{ number_format($record->total_impressions) }}</td>
                        <td>{{ number_format($record->total_clicks) }}</td>
                        <td>{{ number_format($record->avg_ctr * 100, 2) }}%</td>
                        <td>{{ number_format($record->avg_position, 2) }}</td>
                        <td>{{ $record->start_date->format('Y-m-d') }}</td>
                        <td>{{ $record->end_date->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
@include('components.footer')
</html>
