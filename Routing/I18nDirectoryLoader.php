<?php

namespace Snowcap\I18nBundle\Routing;

use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;

class I18nDirectoryLoader extends AnnotationDirectoryLoader {
    /**
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        try {
            $path = $this->locator->locate($resource);
        } catch (\Exception $e) {
            return false;
        }

        return is_string($resource) && is_dir($path) && (!$type || 'i18n' === $type);
    }
}