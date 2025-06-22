    @php
        use App\Helpers\BreadcrumbHelper;
        $groups = BreadcrumbHelper::getLinks();
    @endphp

    @foreach ($groups as $groupName => $pages)
        <section class="group">
            <h2>{{ $groupName }}</h2>
            <ul class="link-grid">
                @foreach ($pages as $page)
                    <li><a href="{{ $page['url'] }}">{{ $page['name'] }}</a></li>
                @endforeach
            </ul>
        </section>
    @endforeach
