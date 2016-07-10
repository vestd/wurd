<?php

namespace Vestd\Wurd\CacheProvider;

use League\Flysystem;

class LaravelCacheProvider implements CacheProviderInterface
{
    protected $filesystem;
    protected $filePath;
    protected $fileName;
    protected $maxAge;

    public function __construct(Flysystem\Filesystem $filesystem, $filePath, $maxAge, $fileName = "wurd.json")
    {
        $this->filesystem = $filesystem;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->maxAge = $maxAge;
    }

    public function getPage($page)
    {
        if ($contents = $this->read()) {
            if (array_key_exists($page, $contents)) {
                return $contents[$page];
            }
        }

        return false;
    }

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

    public function storePage($page, $contents)
    {
        $contents = (object)[$page => $contents];
        $this->write($contents);
    }

    public function storeLanguage($language, $contents)
    {
        $this->write($contents, $language);
    }

    public function storeApp($json)
    {
        // TODO: Implement storeApp() method.
    }

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

    protected function write($contents, $language = null)
    {
        $contents->updated_at = date("Y-m-d H:i:s");

        $this->filesystem->put($this->filePath($language), json_encode($contents));
    }

    protected function filePath($language = null)
    {
        if ($language) {
            return $this->filePath . $language . "/" . $this->fileName;
        }

        return $this->filePath . $this->fileName;
    }

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