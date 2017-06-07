<?php

namespace AudienceHero\Bundle\ImageServerBundle\Transformer;

use Imagine\Image\Box;
use Imagine\Imagick\Image;

/**
 * ImageResizerTransformer.
 *
 * @author Marc Weistroff <marc@weistroff.net> 
 */
class ImageResizerTransformer implements TransformerInterface
{
    public function transform(Image $image, array $options): void
    {
        if ($options['content_type'] === 'image/gif') {
            return;
        }

        if (0 === $options['height']) {
            $image->resize($image->getSize()->widen($options['width']));

            return;
        }

        if (0 === $options['width']) {
            $image->resize($image->getSize()->heighten($options['height']));

            return;
        }

        $image->resize(new Box($options['width'], $options['height']));
    }
}
