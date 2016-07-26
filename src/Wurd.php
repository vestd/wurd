<?php

namespace Vestd\Wurd;

use GuzzleHttp\Client;
use Vestd\Wurd\CacheProvider\CacheProviderInterface;
use Vestd\Wurd\CacheProvider\FlysystemCacheProvider;
use Vestd\Wurd\Exception\HttpException;

class Wurd
{

    /**
     * The name of your app on Wurd
     *
     * @var string
     */
    protected $appName;

    /**
     * The Cache Provider to use
     *
     * @var CacheProviderInterface|FlysystemCacheProvider
     */
    protected $cache;

    /**
     * Base url for the api
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * GET parameters to send to the api
     *
     * @var array
     */
    protected $options;

    /**
     * Wurd constructor.
     * @param string $appName
     * @param CacheProviderInterface $cacheProvider
     * @param array $options
     * @param string $baseUrl
     */
    public function __construct($appName, CacheProviderInterface $cacheProvider = null, $options = [], $baseUrl = "https://api.wurd.io/v2/content/")
    {
        if ($cacheProvider === null) {
            $adapter = new \League\Flysystem\Adapter\Local(__DIR__ . '/');
            $filesystem = new \League\Flysystem\Filesystem($adapter);
            $cacheProvider = new FlysystemCacheProvider($filesystem, 60);
        }

        $this->appName = $appName;
        $this->cache   = $cacheProvider;
        $this->options = $options;
        $this->baseUrl = $baseUrl;

    }

    /**
     * @param string|null $language
     * @param array $options
     * @return mixed
     */
    public function language($language = null, $options = [])
    {
        if ($content = $this->cache->getLanguage(($language))){
            return $content;
        }

        $content = $this->request(null, $language, $options);

        if ($content) {
            $this->cache->storeLanguage($language, $content);
        }

        return $content;
    }

    /**
     * @param $pages
     * @param null $language
     * @param array $options
     * @return \stdClass
     */
    public function pages($pages, $language = null, $options = [])
    {
        if (!is_array($pages)) {
            $pages = [$pages];
        }

        $content = new \stdClass();
        $pagesToRetrieve = [];

        foreach ($pages as $page) {
            $pageContent = $this->cache->getPage($page, $language);

            if ($pageContent){
                $content->$page = $pageContent;
            } else {
                $pagesToRetrieve[] = $page;
            }
        }

        if (!empty($pagesToRetrieve)) {
            $retrievedContents = $this->request($pagesToRetrieve, $language, $options);
            if ($retrievedContents) {
                foreach ($retrievedContents as $page => $retrievedPageContent) {
                    $this->cache->storePage($page, $retrievedPageContent, $language);
                    $content->$page = $retrievedPageContent;
                }
            }
        }

        return $content;
    }

    /**
     * @param array $pages
     * @param string|null $language
     * @param array $options
     * @return mixed
     */
    protected function request($pages, $language, $options = [])
    {
        if (empty($pages)) {
            return [];
        }

        $uri = $this->segments(
            $pages,
            array_merge(
                [
                    'lang' => $language ?: ''
                ],
                $options ?: $this->options
            )
        );

        $client = new Client();
        $res = $client->request(
            'GET',
            $uri
        );

        if ((int)$res->getStatusCode() !== 200){
            throw new HttpException();
        }

        return json_decode($res->getBody());
    }

    /**
     * @param array|null $pages
     * @param array $gets
     * @return string
     */
    protected function segments($pages = [], $gets = [])
    {
        $getString = '?';

        foreach ($gets as $key => $value) {
            $getString .= $key . '=' . $value .'&';
        }

        if ($pages) {
            return $this->baseUrl . $this->appName . '/' . implode(',', $pages) . $getString;
        }

        return $this->baseUrl . '/' . $this->appName . $getString;
    }
}
