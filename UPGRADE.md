Upgrade
=======

Upgrade from pre-1.0 versions
-----------------------------

* TranslatableTrait and TranslationTrait are now directly within the Snowcap\I18nBundle\Entity namespace (and thus no
  longer in the Snowcap\I18nBundle\Entity\Helper namespace)
* The interfaces have been moved to the Snowcap\I18nBundle\Model namespace
* TranslatableTrait::setTranslations() must now be passed an array (and no longer an ArrayCollection instance)
* Initializing the translations ArrayCollection instance in the constructor of your class is no longer necessary