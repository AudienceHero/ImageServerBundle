<?php

namespace AudienceHero\Bundle\ImageServerBundle\Domain;

use Symfony\Component\Validator\Constraints as Assert;

class ImgRequest
{
    /**
     * @var null|string
     *
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $url;

    /**
     * @var null|string
     *
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $size;

    /**
     * @var null|string
     * @Assert\Choice(choices={"square", "square-center", "none"}, strict=true)
     */
    private $crop;

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setSize($size): void
    {
        $this->size = $size;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function getWidth(): int
    {
        return explode('x', $this->size)[0] ?: 0;
    }

    public function getHeight(): int
    {
        return explode('x', $this->size)[1] ?: 0;
    }

    public function setCrop($crop)
    {
        $this->crop = $crop;
    }

    public function getCrop()
    {
        return $this->crop;
    }

    public function toArray(): array
    {
        $a = [];

        $a['url'] = $this->getUrl();
        $a['size'] = $this->getSize();
        $a['crop'] = $this->getCrop();
        $a['width'] = $this->getWidth();
        $a['height'] = $this->getHeight();

        return $a;
    }
}
