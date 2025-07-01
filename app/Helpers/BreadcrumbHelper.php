<?php

namespace App\Helpers;

class BreadcrumbHelper
{
// TOPのリンク一覧、フッターのリンク一覧
public static function getLinks(): array
{
    return [
        'GA4' => [
            'ga4_qsha_oh' => ['name' => 'GA4 一覧', 'url' => route('ga4_qsha_oh')],
            'ga4_qsha_oh.maker' => ['name' => 'GA4 /maker/', 'url' => route('ga4_qsha_oh.maker')],
            'ga4_qsha_oh.result' => ['name' => 'GA4 /result/', 'url' => route('ga4_qsha_oh.result')],
            'ga4_qsha_oh.usersvoice' => ['name' => 'GA4 /usersvoice/', 'url' => route('ga4_qsha_oh.usersvoice')],
            'ga4_qsha_oh.historia' => ['name' => 'GA4 /historia/', 'url' => route('ga4_qsha_oh.historia')],
            'ga4_qsha_oh.yoy' => ['name' => 'GA4 YoY比較', 'url' => route('ga4_qsha_oh.yoy')],
            'ga4_qsha_oh.mom' => ['name' => 'GA4 MoM比較', 'url' => route('ga4_qsha_oh.mom')],
        ],
        'GSC' => [
            'gsc_qsha_oh' => ['name' => 'GSC 一覧', 'url' => route('gsc_qsha_oh')],
            'gsc_qsha_oh.maker' => ['name' => 'GSC /maker/', 'url' => route('gsc_qsha_oh.maker')],
            'gsc_qsha_oh.result' => ['name' => 'GSC /result/', 'url' => route('gsc_qsha_oh.result')],
            'gsc_qsha_oh.usersvoice' => ['name' => 'GSC /usersvoice/', 'url' => route('gsc_qsha_oh.usersvoice')],
            'gsc_qsha_oh.historia' => ['name' => 'GSC /historia/', 'url' => route('gsc_qsha_oh.historia')],
            'gsc_qsha_oh.yoy' => ['name' => 'GSC YoY比較', 'url' => route('gsc_qsha_oh.yoy')],
            'gsc_qsha_oh.mom' => ['name' => 'GSC MoM比較', 'url' => route('gsc_qsha_oh.mom')],
        ],
    ];
}

// パンくずリスト
public static function getFlatLinks(): array
{
    return [
        'ga4_qsha_oh' => ['name' => 'GA4 一覧', 'url' => route('ga4_qsha_oh')],
        'ga4_qsha_oh.maker' => ['name' => 'GA4 /maker/', 'url' => route('ga4_qsha_oh.maker')],
        'ga4_qsha_oh.result' => ['name' => 'GA4 /result/', 'url' => route('ga4_qsha_oh.result')],
        'ga4_qsha_oh.usersvoice' => ['name' => 'GA4 /usersvoice/', 'url' => route('ga4_qsha_oh.usersvoice')],
        'ga4_qsha_oh.yoy' => ['name' => 'GA4 YoY比較', 'url' => route('ga4_qsha_oh.yoy')],
        'ga4_qsha_oh.mom' => ['name' => 'GA4 MoM比較', 'url' => route('ga4_qsha_oh.mom')],

        'gsc_qsha_oh' => ['name' => 'GSC 一覧', 'url' => route('gsc_qsha_oh')],
        'gsc_qsha_oh.maker' => ['name' => 'GSC /maker/', 'url' => route('gsc_qsha_oh.maker')],
        'gsc_qsha_oh.result' => ['name' => 'GSC /result/', 'url' => route('gsc_qsha_oh.result')],
        'gsc_qsha_oh.usersvoice' => ['name' => 'GSC /usersvoice/', 'url' => route('gsc_qsha_oh.usersvoice')],
        'gsc_qsha_oh.yoy' => ['name' => 'GSC YoY比較', 'url' => route('gsc_qsha_oh.yoy')],
        'gsc_qsha_oh.mom' => ['name' => 'GSC MoM比較', 'url' => route('gsc_qsha_oh.mom')],
    ];
}

// ② パンくず用
public static function generate(): array
{
    $currentRoute = \Route::currentRouteName();
    $links = self::getFlatLinks();

    $breadcrumbs = [];

    // 一致するルートを階層的に構築（例: 'ga4_qsha_oh.maker' → ['ga4_qsha_oh', 'ga4_qsha_oh.maker']）
    $segments = explode('.', $currentRoute);
    $routeKey = '';
    foreach ($segments as $segment) {
        $routeKey = $routeKey ? $routeKey . '.' . $segment : $segment;
        if (isset($links[$routeKey])) {
            $breadcrumbs[] = $links[$routeKey];
        }
    }

    return $breadcrumbs;
}

}
