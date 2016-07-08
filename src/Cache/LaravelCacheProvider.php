<?php

namespace Vestd\Wurd\CacheProvider;

use Vestd\Wurd\CacheProviderInterface;
use League\Flysystem;

class LaravelCacheProvider implements CacheProviderInterface
{
    protected $filesystem;
    protected $filePath;

    public function __construct(Flysystem\Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
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
        $this->filesystem->read($this->filePath);
    }

    protected function write($contents)
    {
        $this->filesystem->write($this->filePath, $contents);
    }

}