<?php

namespace AudienceHero\Bundle\ImageServerBundle\Server;

use AudienceHero\Bundle\ImageServerBundle\Loader\LoaderInterface;
use AudienceHero\Bundle\ImageServerBundle\Transformer\TransformerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * ImageServer.
 *
 * @author Marc Weistroff <marc@weistroff.net> 
 */
class Server implements ServerInterface
{
    private $loader;
    private $transformer;

    public function __construct(LoaderInterface $loader, TransformerInterface $transformer)
    {
        $this->loader = $loader;
        $this->transformer = $transformer;
    }

    public function serve(array $r): Response
    {
        $url = $r['url'];
        $width = $r['width'];
        $height = $r['height'];

        $image = $this->loader->load($r['url']);
        $r['content_type'] = $image->getImagick()->getImageMimeType();

        $this->transformer->transform($image, $r);

        switch ($r['content_type']) {
        case 'image/x-jpeg':
        case 'image/jpeg':
            $content = $image->get('jpeg');
            $r['content_type'] = 'image/jpeg';
            break;
        case 'image/x-png':
        case 'image/png':
            $size = $image->getImagick()->getImageLength();
            if ($size > 750 * 1024) {
                $r['content_type'] = 'image/jpeg';
                $content = $image->get('jpeg');
                $r['content_type'] = 'image/jpeg';
            } else {
                $content = $image->get('png');
                $r['content_type'] = 'image/png';
            }
            break;
        case 'image/x-gif':
        case 'image/gif':
            $content = $image->get('gif', ['flatten' => false]);
            $r['content_type'] = 'image/gif';
            break;
        default:
            throw new \RuntimeException(sprintf('I do not know how to handle content type: %s', $r['content_type']));
        }

        $response = new Response($content, 200, [
            'Content-Type' => $r['content_type'],
            'Content-Length' => strlen($content),
        ]);

        $response->setExpires(new \DateTime('+1 year'));
        $response->setMaxAge(86400);

        return $response;
    }
}
