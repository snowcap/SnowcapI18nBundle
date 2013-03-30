<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pierre
 * Date: 30/03/13
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */

namespace Snowcap\I18nBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideRoutingCompilerPass implements CompilerPassInterface {
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('router.class', 'Snowcap\I18nBundle\Routing\I18nRouter');
        $container->getDefinition('router.default')->replaceArgument(4, $container->getParameter('snowcap_i18n.locales'));
    }

}