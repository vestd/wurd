<?php

namespace Vestd\Wurd;

use GuzzleHttp\Client;
use Vestd\Wurd\CacheProvider\CacheProviderInterface;
use Vestd\Wurd\Exception\HttpException;

class Wurd
{

    protected $appName;
    protected $baseUrl;

    /**
     * Wurd constructor.
     * @param $appName
     * @param CacheProviderInterface $cacheProvider
     * @param string $baseUrl
     */
    public function __construct($appName, CacheProviderInterface $cacheProvider, $baseUrl = "https://api.wurd.io/v2/content/")
    {
        $this->appName = $appName;
        $this->baseUrl = $baseUrl;
        $this->cache = $cacheProvider;
    }

    /**
     * @param null $language
     * @return mixed
     * @throws HttpException
     */
    public function language($language = null)
    {
        if ($content = $this->cache->getLanguage(($language))){
            return $content;
        }

        $content = $this->request(null, $language);

        if ($content) {
            $this->cache->storeLanguage($language, $content);
        }

        return $content;
    }

    /**
     * @param $page
     * @param null $language
     * @return mixed
     * @throws HttpException
     */
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

    /**
     * @param $page
     * @param $language
     * @return mixed
     * @throws HttpException
     */
    protected function request($page, $language)
    {
        $client = new Client();
        $res = $client->request('GET', $this->segments($page), [
            'lang' => $language ?: ''
        ]);

        if ((int)$res->getStatusCode() !== 200){
            throw new HttpException();
        }

        return json_decode($res->getBody());
    }

    /**
     * @param null $page
     * @return string
     */
    protected function segments($page = null)
    {
        if ($page) {
            return $this->baseUrl . $this->appName . '/' . $page;
        }

        return $this->baseUrl . '/' . $this->appName;
    }

}