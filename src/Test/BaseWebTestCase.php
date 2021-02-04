<?php


namespace App\Test;

use App\Test\Traits\ReloadDatabaseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class BaseWebTestCase extends WebTestCase
{
    use ReloadDatabaseTrait;
    use Factories;

    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->em = self::$container->get(EntityManagerInterface::class);
    }
}
