<?php

namespace Snowcap\I18nBundle\Routing;

use Doctrine\Common\Annotations\Reader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Routing\RouteCollection;

class I18nClassLoader extends AnnotatedRouteControllerLoader {
    /**
     * @var string
     */
    protected $routeAnnotationClass = 'Snowcap\\I18nBundle\\Annotation\\I18nRoute';

    /**
     * @var array
     */
    private $locales;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader, array $locales)
    {
        parent::__construct($reader);

        $this->locales = $locales;
    }

    /**
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && preg_match('/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/', $resource) && (!$type || 'i18n' === $type);
    }

    /**
     * @param RouteCollection $collection
     * @param $annot
     * @param $globals
     * @param \ReflectionClass $class
     * @param \ReflectionMethod $method
     */
    protected function addRoute(
        RouteCollection $collection,
        $annot,
        $globals,
        \ReflectionClass $class,
        \ReflectionMethod $method
    ) {
        foreach($this->locales as $locale) {
            $annotation = new Route($annot->data);
            $annotation->setName($annotation->getName() . '_' . $locale);
            $annotation->setPattern(trim($locale . '/' . $annotation->getPattern(), '/'));
            $annotation->setDefaults(array_merge($annotation->getDefaults(), array('_locale' => $locale)));

            parent::addRoute($collection, $annotation, $globals, $class, $method);
        }
    }
}