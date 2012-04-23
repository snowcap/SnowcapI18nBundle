<?php

namespace Snowcap\I18nBundle\Routing;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router as BaseRouter;

class Router extends BaseRouter implements RouterInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param  string  $name       The name of the route
     * @param  array   $parameters An array of parameters
     * @param  Boolean $absolute   Whether to generate an absolute URL
     *
     * @return string The generated URL
     *
     * @throws \InvalidArgumentException When the route doesn't exists
     */
    public function generate($name, $parameters = array(), $absolute = false)
    {

        try {
            return $this->router->generate($name, $parameters, $absolute);
        } catch (RouteNotFoundException $e) {

            if (isset($parameters['_locale'])) {
                $locale = $parameters['_locale'];
            } else {
                $locale = $this->getContext()->getParameter('_locale');
            }

            return $this->generateI18n($name, $locale, $parameters, $absolute);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function match($url)
    {
        $match = $this->router->match($url);

        // if a _locale parameter isset remove the .locale suffix that is appended to each route in I18nRoute
        if (!empty($match['_locale']) && preg_match('#^(.+)\.' . preg_quote($match['_locale'], '#') . '+$#', $match['_route'], $route)) {
            $match['_route'] = $route[1];
        }

        return $match;
    }

    public function getRouteCollection()
    {
        return $this->router->getRouteCollection();
    }

    public function setContext(RequestContext $context)
    {
        $this->router->setContext($context);
    }

    public function getContext()
    {
        return $this->router->getContext();
    }

    /**
     * Generates a I18N URL from the given parameter
     *
     * @param string   $name       The name of the I18N route
     * @param string   $locale     The locale of the I18N route
     * @param  array   $parameters An array of parameters
     * @param  Boolean $absolute   Whether to generate an absolute URL
     *
     * @return string The generated URL
     *
     * @throws RouteNotFoundException When the route doesn't exists
     */
    private function generateI18n($name, $locale, $parameters, $absolute)
    {
        try {
            return $this->router->generate($name . '.' . $locale, $parameters, $absolute);
        } catch (RouteNotFoundException $e) {
            throw new RouteNotFoundException(sprintf('I18nRoute "%s" (%s) does not exist.', $name, $locale));
        }
    }
}