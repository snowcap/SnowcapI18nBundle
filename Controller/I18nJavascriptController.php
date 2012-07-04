<?php

namespace Snowcap\I18nBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Finder\Finder;

class I18nJavascriptController extends Controller {

    /**
     * @param string $domain
     * @param string $locale
     *
     * @Template()
     */
    public function catalogAction($domains) {

        $domainArray = explode(',', $domains);

        $translator = $this->get('translator');/* @var \Snowcap\I18nBundle\Translation\Translator $translator */

        $locales = $this->container->getParameter('locales');
        $translations = array();

        foreach($locales as $locale) {
            $translations[$locale] = array();
            $catalogue = $translator->getCatalogue($locale);
            foreach($domainArray as $domainName) {
                if(!in_array($domainName, $catalogue->getDomains())) {
                    throw new \UnexpectedValueException(sprintf('The domain %s does not exist', $domainName));
                }
                $translations[$locale][$domainName] = $catalogue->all($domainName);
            }
        }

        return array('translations' => $translations);
    }
}