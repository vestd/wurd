<?php

namespace Vestd\Wurd;

use GuzzleHttp\Client;
use Vestd\Wurd\Exception\HttpException;

class Wurd
{

    protected $appName;
    protected $baseUrl;

    public function __construct($appName, CacheProviderInterface $cacheProvider, $baseUrl = "https://api.wurd.io/v2/content/")
    {
        $this->appName = $appName;
        $this->baseUrl = $baseUrl;
        $this->cache = $cacheProvider;
    }

    public function language($language = null)
    {
        return $this->request(null, $language);
    }

    public function page($page, $language = null)
    {
        if ($content = $this->cache->getPage(($page))){
            return $content;
        }

        $content = $this->request($page, $language);

        if ($content) {
            $this->cache->storePage($page, $content);
        }

        return $content;
    }

    protected function request($page, $language)
    {
        echo $this->segments($page);
        $client = new Client();
        $res = $client->request('GET', $this->segments($page), [
            'lang' => $language ?: ''
        ]);

        if ((int)$res->getStatusCode() !== 200){
            throw new HttpException();
        }

        return $res->getBody();
    }
    
    protected function segments($page = null)
    {
        if ($page) {
            return $this->baseUrl . '/' . $this->appName . '/' . $page;
        }

        return $this->baseUrl . '/' . $this->appName;
    }

}