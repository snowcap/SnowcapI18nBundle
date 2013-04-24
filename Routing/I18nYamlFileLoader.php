<?php

namespace Snowcap\I18nBundle\Routing;

use Snowcap\I18nBundle\Registry;
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
    private $registry;

    /**
     * @param FileLocatorInterface $locator
     * @param array $locales
     * @param string $translationDomain
     */
    public function __construct(FileLocatorInterface $locator, I18nLoaderHelper $helper, Registry $registry)
    {
        parent::__construct($locator);

        $this->helper = $helper;
        $this->registry = $registry;
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
        $i18n = isset($config['i18n']) ? $config['i18n'] : true;

        foreach($this->registry->getRegisteredLocales() as $locale) {
            $route = new Route($config['path'], $defaults, $requirements, $options, $host, $schemes, $methods);

            if($i18n) {
                $route->setPath('/' . $locale . $this->helper->alterPath($config['path'], $locale));
                $route->setDefaults($this->helper->alterdefaults($defaults, $locale));
            }

            $i18nName = $i18n ? $this->helper->alterName($name, $locale) : $name;
            $collection->add($i18nName, $route);
        }
    }

    /**
     * @param array $config
     * @param string $name
     * @param string $path
     * @todo Find a better solution ($availableKeys is private so no better way to "override" validate for now)
     */
    protected function validate($config, $name, $path)
    {
        if(isset($config['i18n'])) {
            unset($config['i18n']);
        }
        parent::validate($config, $name, $path);
    }
}
