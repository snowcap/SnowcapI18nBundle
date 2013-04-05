<?php

namespace Snowcap\I18nBundle;


class Registry {
    /**
     * @var array
     */
    private $locales;

    /**
     * @param array $locales
     */
    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }

    /**
     * @param array $locales
     */
    public function registerLocales(array $locales)
    {
        $this->locales = array_unique(array_merge($this->locales, $locales));
    }

    /**
     * @return array
     */
    public function getRegisteredLocales()
    {
        return $this->locales;
    }
}