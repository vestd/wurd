<?php

namespace Vestd\Wurd\Test;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Vestd\Wurd\CacheProvider\FlysystemCacheProvider;

class LaravelCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_marks_content_as_expired()
    {
        $cache = new FlysystemCacheProvider(
            new Filesystem(new Local('/home/vagrant/www/Wurd/Vestd/Wurd/tests/')),
            '/storage/',
            10
        );
    }
}