<?php

namespace AudienceHero\Bundle\ImageServerBundle\Tests\Server;

use Doctrine\Common\Cache\ArrayCache;
use AudienceHero\Bundle\ImageServerBundle\Loader\StreamLoader;
use AudienceHero\Bundle\ImageServerBundle\Server\CacheServer;
use AudienceHero\Bundle\ImageServerBundle\Server\Server;
use AudienceHero\Bundle\ImageServerBundle\Transformer\ChainTransformer;
use PHPUnit\Framework\TestCase;

class CacheServerTest extends TestCase
{
    /** @var CacheServer */
    private $server;

    public function setUp()
    {
        $cache = new ArrayCache();

        $server = new Server(new StreamLoader(), new ChainTransformer([]));
        $this->server = new CacheServer($server, $cache);
    }

    public function testServe()
    {
        $options = [
            'url' => __DIR__.'/../../Resources/fixtures/300.png',
            'width' => 400,
            'height' => 0,
            'crop' => 'none',
        ];

        $response = $this->server->serve($options);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertNull($response->headers->get('X-AudienceHero-Image-Server-Cache-Hit'));

        $response = $this->server->serve($options);
        $this->assertSame(1, $response->headers->get('X-AudienceHero-Image-Server-Cache-Hit'));
    }
}
