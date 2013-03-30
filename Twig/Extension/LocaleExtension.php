<?php

namespace Snowcap\I18nBundle\Twig\Extension;

use \Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Locale\Locale;

use Snowcap\I18nBundle\Util\DateFormatter;

class LocaleExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    private $activeLocales = array();

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $activeLocales
     */
    public function __construct(ContainerInterface $container, array $activeLocales = array())
    {
        $this->container = $container;
        $this->activeLocales = $activeLocales;
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return array(
            '_locale' => $this->container->get('request')->getLocale(),
        );
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array('get_active_locales' => new \Twig_Function_Method($this, 'getActiveLocales'));
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'country' => new \Twig_Filter_Method($this, 'getCountry'),
            'locale_date' => new \Twig_Filter_Method($this, 'getLocaleDate'),
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
        if (empty($this->activeLocales)) {
            return array();
        }
        elseif ($locale === null) {
            $translatedLocales = $this->activeLocales;
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
                }, $this->activeLocales
            );
        }
        return array_combine($this->activeLocales, $translatedLocales);
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

}
