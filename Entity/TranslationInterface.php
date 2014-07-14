<?php

namespace Snowcap\I18nBundle\Entity;


/**
 * Interface TranslationInterface
 *
 * Implement this interface on your translation entity
 *
 * @package Snowcap\I18nBundle\Entity
 */
interface TranslationInterface 
{
    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale);

    /**
     * @return string
     */
    public function getLocale();

    /**
     * Method used to set the translatable entity
     *
     * @param TranslatableInterface $translatable
     * @return $this
     */
    public function setTranslated(TranslatableInterface $translatable);

    /**
     * Method used to define if an entity should be considered as empty
     * This method is useful in a form collection
     * @return boolean
     */
    public function isEmpty();
} 