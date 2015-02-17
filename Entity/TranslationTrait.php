<?php

namespace Snowcap\I18nBundle\Entity;


/**
 * Class TranslationTrait
 *
 * @package Snowcap\I18nBundle\Entity
 */
trait TranslationTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=5)
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