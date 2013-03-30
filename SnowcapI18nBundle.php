<?php
namespace Snowcap\I18nBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Snowcap\I18nBundle\DependencyInjection\Compiler\OverrideRoutingCompilerPass;

class SnowcapI18nBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideRoutingCompilerPass());
    }
}
