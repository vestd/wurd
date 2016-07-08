<?php

namespace Vestd\Wurd;

interface CacheProviderInterface
{
    public function getPage($page);

    public function getLanguage($page);

    public function getApp();

    public function storePage($page, $json);

    public function storeLanguage($language, $json);

    public function storeApp($json);
}