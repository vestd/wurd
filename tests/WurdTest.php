<?php

namespace Vestd\Wurd\Test;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Vestd\Wurd\CacheProvider\LaravelCacheProvider;
use Vestd\Wurd\Wurd;

class WurdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_gets_language_file_from_wurd()
    {
        $filesystem = new Filesystem(new Local('/home/vagrant/www/Wurd/Vestd/Wurd/tests/language.json'));
        $cacheProvider = new LaravelCacheProvider($filesystem, '/home/vagrant/www/Wurd/Vestd/Wurd/tests/language.json');
        $wurd = new Wurd('vestd', $cacheProvider);
        echo($wurd->language());
    }

}