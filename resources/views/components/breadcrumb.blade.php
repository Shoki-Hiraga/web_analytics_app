<link rel="stylesheet" href="{{ asset('css/navi.css') }}">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">TOP</a></li>
        @foreach (\App\Helpers\BreadcrumbHelper::generate() as $index => $crumb)
            @if ($index === count(\App\Helpers\BreadcrumbHelper::generate()) - 1)
                <li class="breadcrumb-item active" aria-current="page">{{ $crumb['name'] }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
