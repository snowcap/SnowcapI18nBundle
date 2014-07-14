<?php

namespace Snowcap\I18nBundle\Entity\Helper;

use Doctrine\Common\Collections\ArrayCollection;
use Snowcap\I18nBundle\Entity\TranslationInterface;

/**
 * Class TranslatableTrait
 *
 * Helper to use on your translatable entity
 * You need to define the "translation" property on your entity,
 * the Trait provides you all the common accessors
 *
 * @package Snowcap\I18nBundle\Entity
 */
trait TranslatableTrait
{
    /**
     * Get all translations for the entity
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $translations
     * @return $this
     */
    public function setTranslations(ArrayCollection $translations)
    {
        $this->translations = $translations;

        return $this;
    }

    /**
     * @param TranslationInterface $translation
     * @return $this
     */
    public function addTranslation(TranslationInterface $translation)
    {
        $translation->setTranslated($this);
        $this->translations[$translation->getLocale()] = $translation;

        return $this;
    }

    /**
     * @param TranslationInterface $translation
     */
    public function removeTranslation(TranslationInterface $translation)
    {
        $this->translations->removeElement($translation);
    }

    /**
     * @param string $locale
     * @return TranslationInterface
     */
    public function getTranslation($locale)
    {
        if ($this->hasTranslation($locale)) {
            return $this->translations->get($locale);
        }

        return $this->translations->first();
    }

    /**
     * @param string $locale
     * @return bool
     */
    public function hasTranslation($locale)
    {
        return $this->translations->containsKey($locale);
    }
}