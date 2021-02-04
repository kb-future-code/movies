<?php


namespace App\Tests\Controller\User;

use App\Factory\User\UserFactory;
use App\Test\BaseWebTestCase;

class UserControllerTest extends BaseWebTestCase
{
    public function testLogin()
    {
        $this->client->request('GET', '/user/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $user = UserFactory::new()->create();

        $this->client->loginUser($user->object());
        $this->client->request('GET', '/user/login');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testRegister()
    {
        $this->client->request('GET', '/user/register');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $user = UserFactory::new()->create();

        $this->client->loginUser($user->object());
        $this->client->request('GET', '/user/register');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $this->client->request('GET', '/user/show');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $user = UserFactory::new()->create();

        $this->client->loginUser($user->object());
        $this->client->request('GET', '/user/show');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit()
    {
        $this->client->request('GET', '/user/edit');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $user = UserFactory::new()->create();

        $this->client->loginUser($user->object());
        $this->client->request('GET', '/user/edit');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
