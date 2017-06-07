<?php

namespace AudienceHero\Bundle\ImageServerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class CacheServerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $cache = $container->getParameter('audience_hero_image_server.doctrine_cache');
        if (!$cache) {
            $container->setAlias('audience_hero_image_server','audience_hero_image_server.server.default');
            return;
        }

        try {
            $cacheDef = $container->getDefinition(sprintf('doctrine_cache.providers.%s', $cache));

            $cacheServer = $container->getDefinition('audience_hero_image_server.server.cache');
            $cacheServer->replaceArgument(1, $cacheDef);

            $container->setAlias('audience_hero_image_server', 'audience_hero_image_server.server.cache');
        } catch(ServiceNotFoundException $e) {
            throw new \RuntimeException(sprintf('There is no doctrine cache provider with key "%s". Please refer to the DoctrineCacheBundle documentation in order to configure your cache.', $cache), 0, $e);
        }
    }
}
