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
    <h2>このWebサイトの設立の背景</h2>

</body>
@include('components.footer')
</html>
