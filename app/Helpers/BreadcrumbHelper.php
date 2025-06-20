<?php

namespace App\Helpers;

class BreadcrumbHelper
{
    // ① 一覧も個別ルートもここから参照する
    public static function getLinks(): array
    {
        return [
            'ga4_qsha_oh' => ['name' => 'GA4 旧車王', 'url' => url('/ga4_qsha_oh')],
            'gsc_qsha_oh' => ['name' => 'GSC 旧車王', 'url' => url('/gsc_qsha_oh')],
        ];
    }

    // ② パンくず用
    public static function generate(): array
    {
        $route = request()->route()?->getName(); // null安全演算子

        $links = self::getLinks();

        return isset($links[$route]) ? [$links[$route]] : [];
    }
}
