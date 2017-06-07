<?php

namespace AudienceHero\Bundle\ImageServerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * ImgControllerTest.
 *
 * @author Marc Weistroff <marc@weistroff.net>
 */
class TestCase extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::clean();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        static::clean();
    }

    public function tearDown()
    {
        parent::tearDown();
        static::clean();
    }

    public function setUp()
    {
        static::clean();
    }

    public static function clean()
    {
        $fs = new Filesystem();
        $fs->remove(__DIR__.'/cache');
        $fs->remove(__DIR__.'/logs');
    }

    /**
     * Generates a random environment name. Needed because otherwise we enter some weird cache conflicts
     * when generating Kernels.
     *
     * @return string
     */
    protected function getRandomEnvironment(): string
    {
        return uniqid('env', false);
    }
}
