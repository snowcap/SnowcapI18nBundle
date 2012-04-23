<?php

namespace Snowcap\I18nBundle\Twig\Extension;

use \Symfony\Component\DependencyInjection\ContainerInterface;

class LocaleExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getGlobals()
    {
        return array(
            '_locale' => $this->container->get('session')->getLocale(),
        );
    }

    public function getName()
    {
        return '_locale';
    }
}
