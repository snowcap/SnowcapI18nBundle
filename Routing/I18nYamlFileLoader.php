<?php

namespace Snowcap\I18nBundle\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

class I18nYamlFileLoader extends YamlFileLoader {
    /**
     * @var I18nLoaderHelper
     */
    private $helper;

    /**
     * @var array
     */
    private $locales;

    /**
     * @param FileLocatorInterface $locator
     * @param array $locales
     * @param string $translationDomain
     */
    public function __construct(FileLocatorInterface $locator, I18nLoaderHelper $helper, array $locales)
    {
        parent::__construct($locator);

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
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'yaml_i18n' === $type);
    }

    protected function parseRoute(RouteCollection $collection, $name, array $config, $path)
    {
        $defaults = isset($config['defaults']) ? $config['defaults'] : array();
        $requirements = isset($config['requirements']) ? $config['requirements'] : array();
        $options = isset($config['options']) ? $config['options'] : array();
        $host = isset($config['host']) ? $config['host'] : '';
        $schemes = isset($config['schemes']) ? $config['schemes'] : array();
        $methods = isset($config['methods']) ? $config['methods'] : array();

        foreach($this->locales as $locale) {
            $i18nName = $this->helper->alterName($name, $locale);
            $i18nPath = $this->helper->alterPath($config['path'], $locale);
            $i18nDefaults = $this->helper->alterdefaults($defaults, $locale);
            $route = new Route($i18nPath, $i18nDefaults, $requirements, $options, $host, $schemes, $methods);
            $collection->add($i18nName, $route);
        }
    }
}