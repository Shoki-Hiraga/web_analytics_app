<!DOCTYPE html>
<html lang="ja">
<head>
    <title>GA4比較データ | @include('components.sitename')</title>
    @include('components.header')
    <link rel="canonical" href="{{ url()->current() }}">
</head>

<body>
    <h1>@include('components.sitename')</h1>

    <h2>GA4 {{ $type }}比較（{{ $current_range }} vs {{ $previous_range }}）</h2>

    <form method="GET" action="{{ url()->current() }}">
        <label>対象期間（開始日）: <input type="date" name="start_date" value="{{ request('start_date') }}"></label>
        <label>対象期間（終了日）: <input type="date" name="end_date" value="{{ request('end_date') }}"></label>
        <button type="submit">比較</button>
    </form>

    <div class="table-container">
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ランディングURL</th>
                    <th>セッションメディア</th>
                    <th>対象期間</th>
                    <th>セッション数</th>
                    <th>CV数</th>
                    <th>CVR（%）</th>
                    <th>セッション数増減率</th>
                    <th>CV数増減率</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($currentData as $current)
                    @php
                        $prev = $previousData->firstWhere('landing_url', $current->landing_url);
                        $prevSessions = $prev->total_sessions ?? 0;
                        $prevCVs = $prev->cv_count ?? 0;
                        $sessionDiff = $prevSessions ? (($current->total_sessions - $prevSessions) / $prevSessions * 100) : null;
                        $cvDiff = $prevCVs ? (($current->cv_count - $prevCVs) / $prevCVs * 100) : null;
                    @endphp
                    <tr>
                        <td>{{ $current->landing_url }}</td>
                        <td>{{ $current->session_medium }}</td>
                        <td>{{ $current_range }}</td>
                        <td>{{ number_format($current->total_sessions) }}</td>
                        <td>{{ number_format($current->cv_count) }}</td>
                        <td>{{ number_format($current->cvr, 2) }}%</td>
                        <td>
                            @if (!is_null($sessionDiff))
                                {{ number_format($sessionDiff, 1) }}%
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if (!is_null($cvDiff))
                                {{ number_format($cvDiff, 1) }}%
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @if($prev)
                        <tr style="opacity: 0.6;">
                            <td colspan="2"></td>
                            <td>{{ $previous_range }}</td>
                            <td>{{ number_format($prev->total_sessions) }}</td>
                            <td>{{ number_format($prev->cv_count) }}</td>
                            <td>{{ number_format($prev->cvr, 2) }}%</td>
                            <td colspan="2"></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <h2>このWebサイトの設立の背景</h2>
</body>
@include('components.footer')
</html>
