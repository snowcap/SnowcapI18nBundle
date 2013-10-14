<?php

namespace Snowcap\I18nBundle\Routing;

use Symfony\Component\Routing\Loader\AnnotationFileLoader;
/**
 * AnnotationFileLoader loads routing information from annotations set
 * on a PHP class and its methods.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class I18nAnnotationFileLoader extends AnnotationFileLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'annotation_i18n' === $type);
    }
}
