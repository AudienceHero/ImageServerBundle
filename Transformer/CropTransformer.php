<?php

namespace AudienceHero\Bundle\ImageServerBundle\Transformer;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Imagick\Image;

/**
 * CropTransformer.
 *
 * @author Marc Weistroff <marc@weistroff.net> 
 */
class CropTransformer implements TransformerInterface
{
    public function transform(Image $image, array $options): void
    {
        if ($options['content_type'] === 'image/gif') {
            return;
        }

        if (!isset($options['crop'])) {
            return;
        }

        if ($options['crop'] === 'none') {
            return;
        }

        if ($options['crop'] === 'square') {
            $box = $image->getSize();
            $side = $box->getWidth() < $box->getHeight() ? $box->getWidth() : $box->getHeight();

            $image->crop(new Point(0, 0), new Box($side, $side));
        }

        if ($options['crop'] === 'square-center') {
            $box = $image->getSize();
            $side = $box->getWidth() < $box->getHeight() ? $box->getWidth() : $box->getHeight();

            $x = 0;
            $y = 0;

            if ($box->getWidth() > $box->getHeight()) {
                $x = floor($box->getWidth() / 2 - $side / 2);
                if ($x < 0) {
                    $x = 0;
                }
            } else {
                $y = floor($box->getHeight() / 2 - $side / 2);
                if ($y < 0) {
                    $y = 0;
                }
            }

            $image->crop(new Point($x, $y), new Box($side, $side));
        }
    }
}
