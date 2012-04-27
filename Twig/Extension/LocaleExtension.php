<?php

namespace Snowcap\I18nBundle\Twig\Extension;

use \Symfony\Component\DependencyInjection\ContainerInterface;

class LocaleExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    private $activeLocales = array();

    public function __construct(ContainerInterface $container, array $activeLocales = array())
    {
        $this->container = $container;
        $this->activeLocales = $activeLocales;
    }

    public function getGlobals()
    {
        return array(
            '_locale' => $this->container->get('session')->getLocale(),
        );
    }

    public function getFunctions()
    {
        return array('get_active_locales' => new \Twig_Function_Method($this, 'getActiveLocales'));
    }

    public function getName()
    {
        return '_locale';
    }

    public function setActiveLocales(array $locales)
    {
        $this->activeLocales = $locales;
    }

    public function getActiveLocales($locale = null)
    {
        if(empty($this->activeLocales)){
            return array();
        }
        elseif($locale === null) {
            $translatedLocales = $this->activeLocales;
        }
        else {
            $displayLocales = Locale::getDisplayLocales($locale);
            $translatedLocales = array_map(function($element) use($displayLocales){
                return $displayLocales[$element];
            }, $this->activeLocales);
        }
        return array_combine($this->activeLocales, $translatedLocales);
    }


}
