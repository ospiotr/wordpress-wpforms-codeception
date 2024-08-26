<?php

namespace Tests\Util;

class PageUtils
{
    public static function getPostIdOnEditScreen($I)
    {
        $currentUrl = $I->grabFromCurrentUrl();
        $parsedUrl = parse_url($currentUrl);
        parse_str($parsedUrl['query'], $queryParams);
        return $queryParams['post'] ?? null;
    }
}
