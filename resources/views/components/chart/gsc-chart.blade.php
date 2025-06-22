<div class="chart-area">
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
</div>