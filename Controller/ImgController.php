<?php

namespace AudienceHero\Bundle\ImageServerBundle\Controller;

use AudienceHero\Bundle\ImageServerBundle\Domain\ImgRequest;
use AudienceHero\Bundle\ImageServerBundle\Form\ImgRequestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ImgController.
 *
 * @author Marc Weistroff <marc@weistroff.net> 
 */
class ImgController extends Controller
{
    public function showAction(Request $request)
    {
        $r = new ImgRequest();
        $f = $this->createForm(ImgRequestType::class, $r);

        if ($size = $request->query->get('amp;size')) {
            $request->query->set('size', $size);
            $request->query->remove('amp;size');
        }


        $data = [
            'size' => $request->get('size'),
            'crop' => $request->get('crop'),
            'url' => urldecode($request->get('url')),
        ];

        if ($request->isMethod('HEAD')) {
            return new Response('', 200);
        }

        if (!$f->submit($data)->isValid()) {
            $this->get('logger')->error('Received invalid request to serve image.', [
                'method' => $request->getMethod(),
                'uri' => $request->getUri(),
                'query_string' => $request->getQueryString(),
                'query' => $request->query->all()
            ]);

            return new Response('Invalid request.', 400);
        }

        return $this->get('audience_hero_image_server')->serve($r->toArray());
    }
}
