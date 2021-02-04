<?php


namespace App\Test\Traits;


use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

trait ReloadDatabaseTrait
{
    protected static function bootKernel(array $options = [])
    {
        static::ensureKernelTestCase();
        $kernel = parent::bootKernel($options);
        static::purgeDatabase();

        return $kernel;
    }

    protected static function ensureKernelTestCase(): void
    {
        if (!is_a(static::class, KernelTestCase::class, true)) {
            throw new LogicException(sprintf('The test class must extend "%s" to use "%s".', KernelTestCase::class, static::class));
        }
    }

    protected static function purgeDatabase(): void
    {
        $container = static::$container ?? static::$kernel->getContainer();

        $purger = new ORMPurger($container->get('doctrine')->getManager());
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();
    }
}
