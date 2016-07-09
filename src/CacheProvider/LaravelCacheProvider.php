<?php

namespace Vestd\Wurd\CacheProvider;

use League\Flysystem;

class LaravelCacheProvider implements CacheProviderInterface
{
    protected $filesystem;
    protected $filePath;

    public function __construct(Flysystem\Filesystem $filesystem, $filePath)
    {
        $this->filesystem = $filesystem;
        $this->filePath = $filePath;
    }

    public function getPage($page)
    {
        if ($contents = json_decode($this->read())) {
            if (array_key_exists($page, $contents)) {
                return $contents[$page];
            }
        }

        return false;
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

    protected function read()
    {
        if (!$this->filesystem->has($this->filePath)) {
            return false;
        }

        $this->filesystem->read($this->filePath);
    }

    protected function write($contents)
    {
        if (!$this->filesystem->has($this->filePath)) {
            $this->filesystem->write($this->filePath, '');
        }

        $this->filesystem->write($this->filePath, json_encode($contents));
    }

}