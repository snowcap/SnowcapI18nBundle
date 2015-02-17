<?php

namespace Snowcap\I18nBundle\Model;


/**
 * Interface TranslatableInterface
 *
 * @package Snowcap\I18nBundle\Entity
 */
interface TranslatableInterface 
{
    /**
     * @param string $locale
     * @return TranslationInterface
     */
    public function getTranslation($locale);
}