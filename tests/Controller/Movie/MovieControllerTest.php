<?php


namespace App\Tests\Controller\Movie;

use App\Factory\User\UserFactory;
use App\Test\BaseWebTestCase;

class MovieControllerTest extends BaseWebTestCase
{
    public function testList()
    {
        $this->client->request('GET', '/movie/list');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $user = UserFactory::new()->create();

        $this->client->loginUser($user->object());
        $this->client->request('GET', '/movie/list');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
