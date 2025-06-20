    <ul>
        @php
            use App\Helpers\BreadcrumbHelper;
            $pages = BreadcrumbHelper::getLinks();
        @endphp

        @foreach ($pages as $page)
            <li><a href="{{ $page['url'] }}">{{ $page['name'] }}</a></li>
        @endforeach
    </ul>