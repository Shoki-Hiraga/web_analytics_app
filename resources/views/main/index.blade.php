<!DOCTYPE html>
<html lang="ja">
<head>
    <title>@include('components.sitename')</title>
    @include('components.header')
    <link rel="canonical" href="{{ url()->current() }}">
</head>

<body>
    <h1>@include('components.sitename') ページ一覧</h1>
    <ul>
        @php
            use App\Helpers\BreadcrumbHelper;
            $pages = BreadcrumbHelper::getLinks();
        @endphp

        @foreach ($pages as $page)
            <h2><li><a href="{{ $page['url'] }}">{{ $page['name'] }}</a></li></h2>
        @endforeach
    </ul>
</body>
</html>
