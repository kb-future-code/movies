<?php


namespace App\Tests\Controller;

use App\Test\BaseWebTestCase;

class AppControllerTest extends BaseWebTestCase
{
    public function testLogin()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
