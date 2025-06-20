<?php

namespace App\Helpers;

class BreadcrumbHelper
{
    // ① 一覧も個別ルートもここから参照する
public static function getLinks(): array
{
    return [
        'ga4_qsha_oh' => ['name' => 'GA4 一覧', 'url' => route('ga4_qsha_oh')],
        'ga4_qsha_oh.maker' => ['name' => 'GA4 /maker/', 'url' => route('ga4_qsha_oh.maker')],
        'ga4_qsha_oh.result' => ['name' => 'GA4 /result/', 'url' => route('ga4_qsha_oh.result')],
        'ga4_qsha_oh.usersvoice' => ['name' => 'GA4 /usersvoice/', 'url' => route('ga4_qsha_oh.usersvoice')],
        
        'gsc_qsha_oh' => ['name' => 'GSC 一覧', 'url' => route('gsc_qsha_oh')],
        'gsc_qsha_oh.maker' => ['name' => 'GSC /maker/', 'url' => route('gsc_qsha_oh.maker')],
        'gsc_qsha_oh.result' => ['name' => 'GSC /result/', 'url' => route('gsc_qsha_oh.result')],
        'gsc_qsha_oh.usersvoice' => ['name' => 'GSC /usersvoice/', 'url' => route('gsc_qsha_oh.usersvoice')],
    ];
}

// ② パンくず用
public static function generate(): array
{
    $route = request()->route()?->getName();
    $map = self::getLinks();

    // 例: 'ga4_qsha_oh.maker' → ['ga4_qsha_oh', 'ga4_qsha_oh.maker']
    $breadcrumbs = [];

    if ($route && isset($map[$route])) {
        // 上位ページ（一覧）を自動的に補完
        if (str_contains($route, '.')) {
            $parent = explode('.', $route)[0]; // ga4_qsha_oh
            if (isset($map[$parent])) {
                $breadcrumbs[] = $map[$parent];
            }
        }
        $breadcrumbs[] = $map[$route];
    }

    return $breadcrumbs;
}

}
