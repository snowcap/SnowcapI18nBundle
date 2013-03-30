<?php

namespace Snowcap\I18nBundle\Routing;

use Doctrine\Common\Annotations\Reader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Translation\TranslatorInterface;

class I18nClassLoader extends AnnotatedRouteControllerLoader {
    /**
     * @var string
     */
    protected $routeAnnotationClass = 'Snowcap\\I18nBundle\\Annotation\\I18nRoute';

    /**
     * @var I18nLoaderHelper
     */
    private $helper;

    /**
     * @var array
     */
    private $locales;

    /**
     * @param Reader $reader
     * @param TranslatorInterface $translator
     * @param array $locales
     * @param string $translationDomain
     */
    public function __construct(Reader $reader, I18nLoaderHelper $helper, array $locales)
    {
        parent::__construct($reader);

        $this->helper = $helper;
        $this->locales = $locales;
    }

    /**
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && preg_match('/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/', $resource) && (!$type || 'annotation_i18n' === $type);
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
            $i18nAnnot = new Route($annot->data);
            $i18nAnnot->setName($this->helper->alterName($i18nAnnot->getName(), $locale));
            $i18nAnnot->setPath($this->helper->alterPath($i18nAnnot->getPath(), $locale));
            $i18nAnnot->setDefaults($this->helper->alterdefaults($i18nAnnot->getDefaults(), $locale));

            parent::addRoute($collection, $i18nAnnot, $globals, $class, $method);
        }
    }
}