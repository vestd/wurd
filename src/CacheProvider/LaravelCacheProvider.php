<?php

namespace Vestd\Wurd\CacheProvider;

use League\Flysystem;

class LaravelCacheProvider implements CacheProviderInterface
{
    protected $filesystem;
    protected $filePath;
    protected $fileName;
    protected $maxAge;

    /**
     * LaravelCacheProvider constructor.
     * @param Flysystem\Filesystem $filesystem
     * @param $filePath
     * @param $maxAge in minutes
     * @param string $fileName
     */
    public function __construct(Flysystem\Filesystem $filesystem, $filePath, $maxAge, $fileName = "wurd.json")
    {
        $this->filesystem = $filesystem;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->maxAge = $maxAge;
    }

    /**
     * @param $page
     * @return bool
     */
    public function getPage($page)
    {
        if ($contents = $this->read()) {
            if (array_key_exists($page, $contents)) {
                return $contents[$page];
            }
        }

        return false;
    }

    /**
     * @param $language
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
     * @param $page
     * @param $contents
     */
    public function storePage($page, $contents)
    {
        $contents = (object)[$page => $contents];
        $this->write($contents);
    }

    /**
     * @param $language
     * @param $contents
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
     * @param null $language
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
     * @param $contents
     * @param null $language
     */
    protected function write($contents, $language = null)
    {
        $contents->updated_at = date("Y-m-d H:i:s");

        $this->filesystem->put($this->filePath($language), json_encode($contents));
    }

    /**
     * @param null $language
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
     * @param $contents
     * @return bool
     */
    protected function expired($contents)
    {
        if (!property_exists($contents, 'updated_at')) {
            return true;
        }
        if (time() > strtotime('+'.$this->maxAge.' minutes', strtotime($contents->updated_at))) {
            return true;
        }

        return false;
    }

}