<?php

namespace Snowcap\I18nBundle\Tests\Functional\Controller;

use Snowcap\I18nBundle\Tests\Functional\WebTestCase;

class I18nJavascriptControllerTest extends WebTestCase {

    public function testCatalogAction() {
        $client = $this->createClient();
        $client->request('GET', '/i18n/catalog/messages');
        $response = $client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals($response->headers->get('Content-type'), 'application/javascript');
    }

}