<?php

namespace Snowcap\I18nBundle\Entity\Helper;

/**
 * Class TranslationTrait
 *
 * Helper to use on your translation entity
 *
 * @package Snowcap\I18nBundle\Entity
 */
trait TranslationTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2)
     */
    protected $locale;

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}