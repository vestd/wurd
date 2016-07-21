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

    /**
     * @test
     */
    public function it_gets_content_from_cache()
    {
        $filesystem = new Filesystem(new Local('/home/vagrant/www/Wurd/Vestd/Wurd/tests/storage/'));
        $cacheProvider = new FlysystemCacheProvider($filesystem, 1);
        $wurd = new Wurd('apitest', $cacheProvider);

        $cacheProvider->storePage(
            'fromtest',
            (object)[
                'teststring' => 'from test'
            ]
        );

        $content = $wurd->pages('fromtest');

        $this->assertEquals('from test', $content->fromtest->teststring);
    }

    /**
     * @test
     */
    public function it_loads_default_cache_provider()
    {
        new Wurd('apitest');
    }

    /**
     * @test
     */
    public function it_gets_pages()
    {
        $wurd = new Wurd('apitest');
        $content = $wurd->pages(['testpage', 'testpage2']);

        $this->assertEquals('test string', $content->testpage->teststring);
        $this->assertEquals('test string 2', $content->testpage2->teststring2);
    }

    /**
     * @test
     */
    public function it_gets_pages_in_language()
    {
        $language = 'lang2';
        $wurd = new Wurd('apitest');
        $content = $wurd->pages('testpage', $language);

        $this->assertEquals('lang2 test string', $content->testpage->teststring);
    }

    /**
     * @test
     */
    public function it_gets_draft_content()
    {
        $wurd = new Wurd('apitest');
        $content = $wurd->pages('testpage', null, ['draft' => true]);

        $this->assertEquals('test string draft', $content->testpage->teststring);
    }

}