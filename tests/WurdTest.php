<?php

namespace Vestd\Wurd\Test;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Vestd\Wurd\CacheProvider\FlysystemCacheProvider;
use Vestd\Wurd\Wurd;

class WurdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_gets_default_language_file_from_wurd()
    {
        $filesystem = new Filesystem(new Local('/home/vagrant/www/Wurd/Vestd/Wurd/tests/storage/'));
        $cacheProvider = new FlysystemCacheProvider($filesystem, 0);
        $wurd = new Wurd('apitest', $cacheProvider);
        $content = $wurd->language();

        $this->assertEquals('test string', $content->testpage->teststring);
    }

}