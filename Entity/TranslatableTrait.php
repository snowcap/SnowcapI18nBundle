<?php

namespace Snowcap\I18nBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Snowcap\I18nBundle\Model\TranslationInterface;

/**
 * Class TranslatableTrait
 *
 * You need to overwrite the "translation" property on your entity (this Trait provides only the shortcut methods)
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
        $this->initTranslations();

        return $this->translations;
    }

    /**
     * @param array $translations
     * @return $this
     */
    public function setTranslations(array $translations)
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
        $this->initTranslations();

        if (!$this->translations->contains($translation)) {
            $translation->setTranslated($this);
            $this->translations->set($translation->getLocale(), $translation);
        }

        return $this;
    }

    /**
     * @param TranslationInterface $translation
     */
    public function removeTranslation(TranslationInterface $translation)
    {
        $this->initTranslations();

        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
        }
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

        return null;
    }

    /**
     * @param string $locale
     * @return bool
     */
    public function hasTranslation($locale)
    {
        $this->initTranslations();

        return $this->translations->containsKey($locale);
    }

    /**
     * Create a new instance of ArrayCollection if needed
     *
     */
    private function initTranslations()
    {
        if(!$this->translations instanceof ArrayCollection) {
            $this->translations = new ArrayCollection();
        }
    }
}