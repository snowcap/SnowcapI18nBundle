<?php

namespace Snowcap\I18nBundle\Routing;

use Snowcap\I18nBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;

class I18nRouter extends Router {
    /**
     * @var \Snowcap\I18nBundle\Registry
     */
    private $registry;

    /**
     * @param ContainerInterface $container
     * @param string $resource
     * @param array $options
     * @param RequestContext $context
     * @param Registry $registry
     */
    public function __construct(
        ContainerInterface $container,
        $resource,
        array $options = array(),
        RequestContext $context = null,
        Registry $registry
    ){
        parent::__construct($container, $resource, $options, $context);

        $this->registry = $registry;
    }

    /**
     * @param string $url
     * @return array
     */
    public function match($url)
    {
        $match = parent::match($url);

        // if a _locale parameter isset remove the .locale suffix that is appended to each route in I18nRoute
        if (!empty($match['_locale']) && preg_match('#^(.+)\.' . preg_quote($match['_locale'], '#') . '+$#', $match['_route'], $route)) {
            $match['_route'] = $route[1];
        }

        return $match;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool|string $referenceType
     * @throws \UnexpectedValueException
     * @return string
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        $originalParameters = $parameters;
        try {
            if(isset($parameters['_locale'])) {
                $locale = $parameters['_locale'];
            }
            else {
                $locale = $this->getContext()->getParameter('_locale');
            }
            if(!in_array($locale, $this->registry->getRegisteredLocales())) {
                throw new \UnexpectedValueException(sprintf('The locale %s has not ben registered in the snowcap_i18n config', $locale));
            }
            $i18nName = $name . '.' . $locale;
            unset($parameters['_locale']);

            return parent::generate($i18nName, $parameters, $referenceType);
        }
        catch(RouteNotFoundException $e) {
            return parent::generate($name, $originalParameters, $referenceType);
        }
    }
}