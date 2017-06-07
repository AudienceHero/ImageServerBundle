<?php

namespace AudienceHero\Bundle\ImageServerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * ImgControllerTest.
 *
 * @author Marc Weistroff <marc@weistroff.net> 
 */
class ImgControllerWithCacheTest extends TestCase
{
    /** @var  Client */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $kernel = new class($this->getRandomEnvironment(), true) extends Kernel {
            public function registerBundles() {
                return [
                    new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
                    new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
                    new \Symfony\Bundle\MonologBundle\MonologBundle(),
                    new \AudienceHero\Bundle\ImageServerBundle\AudienceHeroImageServerBundle(),
                ];
            }

            public function registerContainerConfiguration(LoaderInterface $loader)
            {
                $loader->load(__DIR__.'/../../Resources/fixtures/config_cache.yml');
            }
        };

        $kernel->boot();
        $this->client = $kernel->getContainer()->get('test.client');
    }

    public function testImageServerReturnsA200()
    {
        $host = ['HTTP_HOST' => 'img.example.com'];

        $url = sprintf('http://%s/?%s', 'img.example.com', http_build_query(['url' => 'http://i.imgur.com/sXVYDuCh.jpg', 'size' => '300x0', 'crop' => 'square']));
        $this->client->request('GET', $url, [], [], $host);
        $r = $this->client->getResponse();
        $this->assertEquals(200, $r->getStatusCode());
        $this->assertTrue('image/x-jpeg' === $r->headers->get('Content-Type') || 'image/jpeg' === $r->headers->get('Content-Type'));
        $this->assertNull($r->headers->get('X-AudienceHero-Image-Server-Cache-Hit'));

        $this->client->request('GET', $url, [], [], $host);
        $r = $this->client->getResponse();
        $this->assertSame(1, $r->headers->get('X-AudienceHero-Image-Server-Cache-Hit'));
    }
}
