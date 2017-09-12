<?php

namespace AudienceHero\Bundle\ImageServerBundle\Tests\Server;

use AudienceHero\Bundle\ImageServerBundle\Loader\StreamLoader;
use AudienceHero\Bundle\ImageServerBundle\Server\Server;
use AudienceHero\Bundle\ImageServerBundle\Transformer\ChainTransformer;
use AudienceHero\Bundle\ImageServerBundle\Transformer\CropTransformer;
use AudienceHero\Bundle\ImageServerBundle\Transformer\ImageResizerTransformer;
use Imagine\Imagick\Imagine;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    /** @var Server */
    private $server;

    public function setUp()
    {
        $this->loader = new StreamLoader();
        $this->transformer = new ChainTransformer([
            new ImageResizerTransformer(),
            new CropTransformer(),
        ]);
        $this->server = new Server($this->loader, $this->transformer);
    }

    public function testServe()
    {
        $response = $this->server->serve([
            'url' => 'http://lorempixel.com/288/290/',
            'width' => 240,
            'height' => 240,
        ]);

        $this->assertTrue($response instanceof \Symfony\Component\HttpFoundation\Response);
        $this->assertEquals(86400, $response->getMaxAge());

        $imagine = new Imagine();
        $image = $imagine->load($response->getContent());
        $size = $image->getSize();

        $this->assertEquals(240, $size->getWidth());
        $this->assertEquals(240, $size->getHeight());
    }

    public function testServeAndCrop()
    {
        $response = $this->server->serve([
            'url' => 'http://lorempixel.com/288/200/',
            'width' => 0,
            'height' => 100,

        ]);
        $imagine = new Imagine();
        $image = $imagine->load($response->getContent());
        $size = $image->getSize();
        $this->assertSame(100, $size->getHeight());
        $this->assertGreaterThanOrEqual(144, $size->getWidth());

        $response = $this->server->serve([
            'url' => 'http://lorempixel.com/288/200/',
            'width' => 0,
            'height' => 100,
            'crop' => 'square',

        ]);
        $imagine = new Imagine();
        $image = $imagine->load($response->getContent());
        $size = $image->getSize();
        $this->assertSame(100, $size->getHeight());
        $this->assertSame(100, $size->getWidth());

        $response = $this->server->serve([
            'url' => 'http://lorempixel.com/288/200/',
            'width' => 0,
            'height' => 100,
            'crop' => 'square-center',
        ]);
        $imagine = new Imagine();
        $image = $imagine->load($response->getContent());
        $size = $image->getSize();
        $this->assertSame(100, $size->getHeight());
        $this->assertSame(100, $size->getWidth());
    }
}
