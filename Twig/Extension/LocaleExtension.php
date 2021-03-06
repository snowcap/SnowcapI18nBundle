<?php

namespace Snowcap\I18nBundle\Twig\Extension;

use Snowcap\I18nBundle\Registry;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Locale\Locale;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;

use Snowcap\I18nBundle\Util\DateFormatter;

class LocaleExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Snowcap\I18nBundle\Registry
     */
    private $registry;

    /**
     * @param ContainerInterface $container
     * @param Registry $registry
     */
    public function __construct(ContainerInterface $container, Registry $registry)
    {
        $this->container = $container;
        $this->registry = $registry;
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        $locales = $this->registry->getRegisteredLocales();

        try {
            $locale = $this->container->get('request')->getLocale();
        } catch (InactiveScopeException $e) {
            $locale = current($locales);
        }

        return array(
            '_locale' => $locale,
            '_locales' => $locales
        );
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_active_locales', array($this, 'getActiveLocales')),
            new \Twig_SimpleFunction('set_locale_switch_paths', array($this, 'setSwitchPaths')),
            new \Twig_SimpleFunction('get_locale_switch_paths', array($this, 'getSwitchPaths')),
            new \Twig_SimpleFunction('add_locale_switch_path', array($this, 'addSwitchPath')),
        );
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('country', array($this, 'getCountry')),
            new \Twig_SimpleFilter('language', array($this, 'getLanguage')),
            new \Twig_SimpleFilter('locale_date', array($this, 'getLocaleDate')),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return '_locale';
    }

    /**
     * Returns all active locales with short and long names
     *
     * @param null $locale
     * @return array
     */
    public function getActiveLocales($locale = null)
    {
        $registeredLocales = $this->registry->getRegisteredLocales();

        if (empty($registeredLocales)) {
            return array();
        }
        elseif ($locale === null) {
            $translatedLocales = $registeredLocales;
        }
        else {
            if (strpos($locale, '_') > 0) {
                $displayCountryLocales = Locale::getDisplayLocales($locale);
                $displayLgLocales = Locale::getDisplayLocales(substr($locale, 0, strpos($locale, '_')));
                $displayLocales = array_merge($displayLgLocales, $displayCountryLocales);
            } else {
                $displayLocales = Locale::getDisplayLocales($locale);
            }

            // time to return a translated locale
            $translatedLocales = array_map(
                function($element) use($displayLocales, $locale)
                {
                    if (array_key_exists($element, $displayLocales)) {
                        // perfect, we have the full translated locale
                        return $displayLocales[$element];
                    } elseif (strpos($element, '_') > 0) {
                        // ow we don't, let's see if it's a locale containing the country
                        list($lg, $country) = explode('_', $element);

                        if (strpos($locale, '_') > 0) {
                            // Yep! it's a full locale. Let's merge the translated countries for the full locale + his parent, the lg
                            $displayCountriesChild = Locale::getDisplayCountries($locale);
                            $displayCountriesParent = Locale::getDisplayCountries(substr($locale, 0, strpos($locale, '_')));
                            $displayCountries = array_merge($displayCountriesParent, $displayCountriesChild);
                        } else {
                            // it's just a lg
                            $displayCountries = Locale::getDisplayCountries($locale);
                        }

                        if (array_key_exists($country, $displayCountries)) {
                            // ok we do have a country translation, let's manually build the full translation string
                            $displayCountry = $displayCountries[$country];
                            $displayLg = Locale::getDisplayLanguage($lg, $locale);

                            return $displayLg . ' (' . $displayCountry . ')';
                        } else {
                            // I give up. I just return the received locale.
                            return $element;
                        }
                    } else {
                        return $element;
                    }
                }, $registeredLocales
            );
        }
        return array_combine($registeredLocales, $translatedLocales);
    }

    /**
     * Translate a country indicator to its locale full name
     * Uses default system locale by default. Pass another locale string to force a different translation
     *
     * @param string $country The country indicator
     * @param string $default The default value if the country does not exist (optional)
     * @param mixed  $locale
     *
     * @return string The localized string
     */
    public function getCountry($country, $default = '', $locale = null)
    {
        $locale = $locale == null ? Locale::getDefault() : $locale;
        $countries = Locale::getDisplayCountries($locale);

        return array_key_exists($country, $countries) ? $countries[$country] : $default;
    }

    /**
     * @param string $locale
     * @param string|null $displayLocale
     * @return string
     */
    public function getLanguage($locale, $displayLocale = null) {
        if(null === $displayLocale) {
            $displayLocale = $locale;
        }
        $languages = Locale::getDisplayLanguages($displayLocale);

        return $languages[$locale];
    }

    /**
     * Translate a timestamp to a localized string representation.
     * Parameters dateType and timeType defines a kind of format. Allowed values are (none|short|medium|long|full).
     * Default is medium for the date and no time.
     * Uses default system locale by default. Pass another locale string to force a different translation.
     * You might not like the default formats, so you can pass a custom pattern as last argument.
     *
     * @param mixed  $date
     * @param string $dateType
     * @param string $timeType
     * @param mixed  $locale
     * @param string $pattern
     *
     * @return string The string representation
     */
    public static function getLocaleDate($date, $dateType = 'medium', $timeType = 'none', $locale = null, $pattern = null)
    {
        $formatter = new DateFormatter();

        return $formatter->format($date, $dateType, $timeType, $locale, $pattern);
    }

    /**
     * @param array $paths
     */
    public function setSwitchPaths(array $paths)
    {
        $this->registry->setSwitchPaths($paths);
    }

    /**
     * @return array
     */
    public function getSwitchPaths()
    {
        return $this->registry->getSwitchPaths();
    }

    /**
     * @param string $locale
     * @param string $path
     */
    public function addSwitchPath($locale, $path)
    {
        $this->registry->addSwitchPath($locale, $path);
    }
}
