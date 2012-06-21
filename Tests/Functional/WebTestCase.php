<?php

namespace Snowcap\I18nBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    static protected function getKernelClass()
    {
        require_once __DIR__ . '/app/AppKernel.php';

        return 'Snowcap\I18nBundle\Tests\Functional\AppKernel';
    }

    static protected function createKernel(array $options = array())
    {
        $class = self::getKernelClass();

        return new $class(
            isset($options['root_config']) ? $options['root_config'] : 'config.yml'
        );
    }
}