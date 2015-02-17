<?php

namespace Snowcap\I18nBundle\Model;


/**
 * Interface TranslationInterface
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
}