<?php

namespace AudienceHero\Bundle\ImageServerBundle\Tests\Domain;

use AudienceHero\Bundle\ImageServerBundle\Domain\ImgRequest;
use PHPUnit\Framework\TestCase;

class ImgRequestTest extends TestCase
{
    private $url = 'https://example.com';
    private $size = '640x480';
    private $crop = 'square-center';
    private $r;

    public function setUp()
    {
        $this->r = new ImgRequest();


        $this->r->setUrl($this->url);
        $this->r->setSize($this->size);
        $this->r->setCrop($this->crop);
    }

    public function testGetUrl()
    {
        $this->assertSame($this->url, $this->r->getUrl());
    }

    public function testGetCrop()
    {
        $this->assertSame($this->crop, $this->r->getCrop());
    }

    public function testGetSize()
    {
        $this->assertSame($this->size, $this->r->getSize());
    }

    public function testGetWidth()
    {
        $this->assertSame(640, $this->r->getWidth());
        $this->r->setSize('0x480');
        $this->assertSame(0, $this->r->getWidth());
    }

    public function testGetHeight()
    {
        $this->assertSame(480, $this->r->getHeight());
        $this->r->setSize('640x0');
        $this->assertSame(0, $this->r->getHeight());
    }

    public function testToArray()
    {
        $expected = [
            'url' => $this->url,
            'size' => $this->size,
            'crop' => $this->crop,
            'width' => 640,
            'height' => 480,
        ];

        $this->assertSame($expected, $this->r->toArray());
    }
}
