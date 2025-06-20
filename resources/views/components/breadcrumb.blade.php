<link rel="stylesheet" href="{{ asset('css/navi.css') }}">
<nav aria-label="breadcrumb">
    @php
        $breadcrumbs = \App\Helpers\BreadcrumbHelper::generate();
    @endphp

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">TOP</a></li>

        @foreach ($breadcrumbs as $index => $crumb)
            @if ($index === count($breadcrumbs) - 1)
                <li class="breadcrumb-item active" aria-current="page">{{ $crumb['name'] }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
