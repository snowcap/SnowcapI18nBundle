<?php

namespace Snowcap\I18nBundle\Routing;

use Symfony\Component\Translation\TranslatorInterface;

class I18nLoaderHelper {
    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $translationDomain;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, $translationDomain)
    {
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    /**
     * @param string $name
     * @param string $locale
     * @return string
     */
    public function alterName($name, $locale)
    {
        return $name . '_' . $locale;
    }

    /**
     * @param $path
     * @param $locale
     * @return string
     */
    public function alterPath($path, $locale)
    {
        $translatedPath = trim($this->translator->trans($path, array(), $this->translationDomain, $locale), '/');

        return rtrim('/' . $translatedPath, '/');
    }

    /**
     * @param array $defaults
     * @param string $locale
     * @return array
     */
    public function alterdefaults(array $defaults, $locale)
    {
        return array_merge($defaults, array('_locale' => $locale));
    }
}