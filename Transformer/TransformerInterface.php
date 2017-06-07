<?php

namespace AudienceHero\Bundle\ImageServerBundle\Transformer;

use Imagine\Imagick\Image;

/**
 * TransformerInterface.
 *
 * @author Marc Weistroff <marc@weistroff.net> 
 */
interface TransformerInterface
{
    /**
     * transform.
     *
     * @param Image $image
     * @param array $options
     */
    public function transform(Image $image, array $options): void;
}
