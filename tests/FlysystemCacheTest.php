<?php

namespace Vestd\Wurd\Test;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Vestd\Wurd\CacheProvider\FlysystemCacheProvider;

class FlysystemCacheTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        $filesystem = new Filesystem(new Local(__DIR__ . '/../'));

        $filesystem->deleteDir('tests/storage');
        if ($filesystem->has('src/lang2')) {
            $filesystem->deleteDir('src/lang2');
        }
        if ($filesystem->has('src/wurd.json')) {
            $filesystem->delete('src/wurd.json');
        }

        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_marks_content_as_expired()
    {
        $filesystem = new Filesystem(new Local(__DIR__ . '/storage/'));
        $cacheProvider = new FlysystemCacheProvider($filesystem, 1);

        $expired = $cacheProvider->expired(
            [
                'updated_at' => date("Y-m-d H:i:s")
            ]
        );

        $this->assertFalse($expired);

        $expired = $cacheProvider->expired(
            [
                'updated_at' => date("Y-m-d H:i:s", strtotime('-2 minutes'))
            ]
        );

        $this->assertTrue($expired);
    }

    /**
     * @test
     */
    public function it_gets_file_path()
    {
        $filesystem = new Filesystem(new Local(__DIR__ . '/storage/'));
        $cacheProvider = new FlysystemCacheProvider($filesystem, 1);

        $filePath = $cacheProvider->filePath();

        $this->assertEquals('wurd.json', $filePath);

        $filesystem = new Filesystem(new Local(__DIR__ . '/storage/'));
        $cacheProvider = new FlysystemCacheProvider($filesystem, 1, 'some/other/folder/', 'copy.json');

        $filePath = $cacheProvider->filePath();

        $this->assertEquals('some/other/folder/copy.json', $filePath);
    }

}