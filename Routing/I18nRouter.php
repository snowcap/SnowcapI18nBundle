<?php

namespace Snowcap\I18nBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;

class I18nRouter extends Router {
    /**
     * @var array
     */
    private $locales;

    /**
     * @param ContainerInterface $container
     * @param mixed $resource
     * @param array $options
     * @param RequestContext $context
     * @param array $locales
     */
    public function __construct(
        ContainerInterface $container,
        $resource,
        array $options = array(),
        RequestContext $context = null,
        array $locales
    ){
        parent::__construct($container, $resource, $options, $context);

        $this->locales = $locales;
    }
    /**
     * @param string $name
     * @param array $parameters
     * @param string $referenceType
     * @return string
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        try {
            if(isset($parameters['_locale'])) {
                $locale = $parameters['_locale'];
            }
            else {
                $locale = $this->getContext()->getParameter('_locale');
            }
            if(!in_array($locale, $this->locales)) {
                throw new \UnexpectedValueException(sprintf('The locale %s has not ben registered in the snowcap_i18n config', $locale));
            }
            $i18nName = $name . '_' . $locale;

            return parent::generate($i18nName, $parameters, $referenceType);
        }
        catch(RouteNotFoundException $e) {
            return parent::generate($name, $parameters, $referenceType);
        }
    }
}