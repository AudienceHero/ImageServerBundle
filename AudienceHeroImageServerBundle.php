<?php

namespace AudienceHero\Bundle\ImageServerBundle;

use AudienceHero\Bundle\ImageServerBundle\DependencyInjection\Compiler\CacheServerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AudienceHeroImageServerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CacheServerCompilerPass());
    }
}
