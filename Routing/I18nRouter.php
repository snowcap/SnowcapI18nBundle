<?php

namespace Snowcap\I18nBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class I18nRouter extends Router {
    /**
     * @param string $name
     * @param array $parameters
     * @param string $referenceType
     * @return string
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        try {
            if(isset($parameters['_locale'])) {
                $locale = $parameters['_locale'];
            }
            else {
                $locale = $this->getContext()->getParameter('_locale');
            }
            $i18nName = $name . '_' . $locale;

            return parent::generate($i18nName, $parameters, $referenceType);
        }
        catch(RouteNotFoundException $e) {
            return parent::generate($name, $parameters, $referenceType);
        }
    }
}