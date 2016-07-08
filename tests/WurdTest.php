<?php

namespace Vestd\Wurd\Test;

use Vestd\Wurd\Wurd;

class TwitterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_gets_language_file_from_wurd()
    {
        $wurd = new Wurd('vestd');
        echo($wurd->language());
    }

}