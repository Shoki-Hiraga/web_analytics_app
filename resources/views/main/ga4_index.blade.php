<!DOCTYPE html>
<html lang="ja">
<head>
    <title>GA4集計データ一覧 | @include('components.sitename')</title>
    @include('components.header')
    <link rel="canonical" href="{{ url()->current() }}">
</head>

<body>
    <h1>@include('components.sitename')</h1>

    <h2>GA4 集計データ @isset($directory)（{{ $directory }}）@endisset</h2>

@include('components.yyyy-mm-form')


<h2>ランディングURL別 チャート</h2>

@php
    $chartEntries = [];

    foreach ($chartDataByUrl ?? [] as $url => $data) {
        $base = md5($url);
        $enhancedData = [];

        foreach ($data as $item) {
            $cvr = ($item['total_sessions'] ?? 0) > 0
                ? round(($item['cv_count'] / $item['total_sessions']) * 100, 2)
                : 0;

            $enhancedData[] = array_merge($item, ['cvr' => $cvr]);
        }

        $chartEntries[] = [
            'label' => $url,
            'data' => $enhancedData,
            'sessionId' => "session_$base",
            'cvId' => "cv_$base",
            'cvrId' => "cvr_$base"
        ];
    }
@endphp

@if (!empty($chartEntries))
    @foreach ($chartEntries as $entry)
        <h3>{{ $entry['label'] }}</h3>

        <p>セッション数</p>
        <canvas id="{{ $entry['sessionId'] }}" height="60"></canvas>

        <p>CV数</p>
        <canvas id="{{ $entry['cvId'] }}" height="60"></canvas>

        <p>CVR（%）</p>
        <canvas id="{{ $entry['cvrId'] }}" height="60"></canvas>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartEntries = @json($chartEntries);

        chartEntries.forEach(entry => {
            const months = entry.data.map(item => item.month);
            const sessions = entry.data.map(item => item.total_sessions);
            const cvs = entry.data.map(item => item.cv_count);
            const cvr = entry.data.map(item => item.cvr);

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

            createChart(entry.sessionId, 'セッション数', sessions, 'bar', 'rgba(54, 162, 235, 0.6)');
            createChart(entry.cvId, 'CV数', cvs, 'bar', 'rgba(255, 159, 64, 0.6)');
            createChart(entry.cvrId, 'CVR（%）', cvr, 'line', 'rgba(255, 99, 132, 1)');
        });
    </script>
@endif


    <div class="table-container">
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ランディングURL</th>
                    <th>セッションメディア</th>
                    <th>セッション数</th>
                    <th>CV数</th>
                    <th>CVR（%）</th>
                    <th>開始日</th>
                    <th>終了日</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr>
                        <td>{{ $record->landing_url }}</td>
                        <td>{{ $record->session_medium }}</td>
                        <td>{{ number_format($record->total_sessions) }}</td>
                        <td>{{ number_format($record->cv_count) }}</td>
                        <td>{{ number_format($record->cvr, 2) }}%</td>
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
