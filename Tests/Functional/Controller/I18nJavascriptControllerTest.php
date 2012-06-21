<?php

namespace Snowcap\I18nBundle\Tests\Functional\Controller;

use Snowcap\I18nBundle\Tests\Functional\WebTestCase;

class I18nJavascriptControllerTest extends WebTestCase {

    public function testFoo() {
        $crawler = $this->createClient()->request('GET', '/i18n/catalog/messages/fr');
    }

}