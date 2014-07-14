<?php

namespace Snowcap\I18nBundle\Form\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Snowcap\I18nBundle\Entity\TranslatableInterface;
use Snowcap\I18nBundle\Entity\TranslationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ManageEmptyTranslationsSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    protected $locales = array();

    /**
     * @var string
     */
    protected $translationClass;

    /**
     * @param array $locales
     * @param $translationClass
     */
    function __construct(array $locales, $translationClass)
    {
        $this->locales = $locales;
        $this->translationClass = $translationClass;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::SUBMIT => 'removeEmptyTranslations',
            FormEvents::PRE_SET_DATA => 'addEmptyTranslations'
        );
    }

    /**
     * @param FormEvent $event
     */
    public function removeEmptyTranslations(FormEvent $event)
    {
        $data = $event->getData();

        if (is_object($data) && $data instanceof TranslatableInterface) {
            /** @var $translations ArrayCollection */
            $translations = $data->getTranslations();

            foreach ($translations as $translation) {
                if ($translation instanceof TranslationInterface) {
                    if ($translation->isEmpty()) {
                        $data->getTranslations()->remove($translation->getLocale());
                    }
                }
            }
            $event->setData($data);
        }
    }

    public function addEmptyTranslations(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        $dataClass = $form->getConfig()->getDataClass();
        if(in_array('Snowcap\I18nBundle\Entity\TranslatableInterface', class_implements($dataClass))) {
            /** @var $availableTranslations ArrayCollection */
            if(null === $data) {
                $data = new $dataClass;
            }
            $availableTranslations = $data->getTranslations();
            foreach($this->locales as $locale) {
                if (!$availableTranslations->containsKey($locale)) {
                    /** @var TranslationInterface $translation */
                    $translation = new $this->translationClass;
                    $translation->setLocale($locale);
                    $data->addTranslation($translation);
                }
            }

            $event->setData($data);
        }
    }
}
