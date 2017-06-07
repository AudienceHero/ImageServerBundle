<?php

namespace AudienceHero\Bundle\ImageServerBundle\Transformer;

use Imagine\Imagick\Image;

/**
 * ChainTransformer.
 *
 * @author Marc Weistroff <marc@weistroff.net> 
 */
class ChainTransformer implements TransformerInterface
{
    /** @var array */
    private $transformers = [];

    public function __construct(array $transformers = [])
    {
        $this->transformers = $transformers;
    }

    public function transform(Image $image, array $options): void
    {
        foreach ($this->transformers as $transformer) {
            $transformer->transform($image, $options);
        }
    }
}
