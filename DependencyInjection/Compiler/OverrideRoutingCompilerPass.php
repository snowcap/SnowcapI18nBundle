<?php

namespace Snowcap\I18nBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideRoutingCompilerPass implements CompilerPassInterface {
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('router.class', 'Snowcap\I18nBundle\Routing\I18nRouter');
        $container->getDefinition('router.default')->replaceArgument(4, $container->getDefinition('snowcap_i18n'));
    }
}