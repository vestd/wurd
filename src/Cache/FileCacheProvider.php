<?php

namespace Vestd\Wurd\CacheProvider;

use Vestd\Wurd\CacheProviderInterface;

class FileCacheProvider implements CacheProviderInterface
{
    public function __construct()
    {
    }

    public function getPage($page)
    {
        // TODO: Implement getPage() method.
    }

    public function getLanguage($page)
    {
        // TODO: Implement getLanguage() method.
    }

    public function getApp()
    {
        // TODO: Implement getApp() method.
    }

    public function storePage($page, $json)
    {
        // TODO: Implement storePage() method.
    }

    public function storeLanguage($language, $json)
    {
        // TODO: Implement storeLanguage() method.
    }

    public function storeApp($json)
    {
        // TODO: Implement storeApp() method.
    }

}