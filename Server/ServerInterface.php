<?php

namespace AudienceHero\Bundle\ImageServerBundle\Server;

use Symfony\Component\HttpFoundation\Response;

/**
 * ServerInterface.
 *
 * @author Marc Weistroff <marc@weistroff.net> 
 */
interface ServerInterface
{
    /**
     * Returns a response based on parameters defined in r
     *
     * @param array $r
     * @return Response
     */
    public function serve(array $r): Response;
}
