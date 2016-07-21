<?php

namespace Vestd\Wurd\CacheProvider;

use League\Flysystem;

class FlysystemCacheProvider implements CacheProviderInterface
{
    protected $filesystem;
    protected $filePath;
    protected $fileName;
    protected $maxAge;

    /**
     * LaravelCacheProvider constructor.
     * @param Flysystem\Filesystem $filesystem
     * @param string $filePath
     * @param int $maxAge in minutes
     * @param string $fileName
     */
    public function __construct(Flysystem\Filesystem $filesystem, $maxAge, $filePath = "", $fileName = "wurd.json")
    {
        $this->filesystem = $filesystem;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->maxAge = $maxAge;
    }

    /**
     * @param string $page
     * @param null $language
     * @return bool
     */
    public function getPage($page, $language = null)
    {
        $contents = $this->read($language);
        if ($contents) {
            if (array_key_exists($page, $contents)) {
                return $contents->$page;
            }
        }

        return false;
    }

    /**
     * @param string $language
     * @return bool|mixed
     */
    public function getLanguage($language)
    {
        if ($contents = $this->read($language)) {
            return $contents;
        }

        return false;
    }

    public function getApp()
    {
        // TODO: Implement getApp() method.
    }

    /**
     * @param string $page
     * @param object $contents
     * @param null $language
     */
    public function storePage($page, $contents, $language = null)
    {
        $contents = (object)[$page => $contents];
        $this->write($contents, $language);
    }

    /**
     * @param string $language
     * @param object $contents
     */
    public function storeLanguage($language, $contents)
    {
        $this->write($contents, $language);
    }

    /**
     * @param $json
     */
    public function storeApp($json)
    {
        // TODO: Implement storeApp() method.
    }

    /**
     * @param string|null $language
     * @return bool|mixed\
     */
    protected function read($language = null)
    {
        if (!$this->filesystem->has($this->filePath($language))) {
            return false;
        }

        $contents = json_decode($this->filesystem->read($this->filePath($language)));

        if ($this->expired($contents)) {
            return false;
        }

        return $contents;
    }

    /**
     * @param object $contents
     * @param null $language
     */
    protected function write($contents, $language = null)
    {
        $contents->updated_at = date("Y-m-d H:i:s");
        $this->filesystem->put($this->filePath($language), json_encode($contents));
    }

    /**
     * @param string|null $language
     * @return string
     */
    protected function filePath($language = null)
    {
        if ($language) {
            return $this->filePath . $language . "/" . $this->fileName;
        }

        return $this->filePath . $this->fileName;
    }

    /**
     * @param object $contents
     * @return bool
     */
    protected function expired($contents)
    {
        if (!property_exists($contents, 'updated_at')) {
            return true;
        }
        if (time() > strtotime('+' . $this->maxAge . ' minutes', strtotime($contents->updated_at))) {
            return true;
        }

        return false;
    }

}