#####################
Interfaces and traits
#####################

Interfaces
==========

SnowcapI18nBundle provides two useful interfaces to help you deal with multilingual content in a structured way:

* SnowcapI18nBundle\Model\TranslatableInterface should be implemented by classes that should act as **translatable**
  content
* SnowcapI1!nBundle\Model\TranslationInterface should be implemented by classes that are **translations** of
  translatable content


Doctrine traits
===============

SnowcapI18nBundle provides two Doctrine traits to help you deal with multilingual entities, by implementing parts of
the two interfaces described aboved:

* SnowcapI18nBundle\Entity\TranslatableTrait
* SnowcapI18nBundle\Entity\TranslationTrait