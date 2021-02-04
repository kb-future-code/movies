<?php

namespace App\Factory\User;

use App\Entity\User\User;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method User|Proxy create($attributes = [])
 */
final class UserFactory extends ModelFactory
{
    const DEFAULT_PASSWORD = 'testPassword';

    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'email' => self::faker()->email,
            'password' => '$2y$13$3cx.FPxfPLPcVpM7VeTmxupUzMAojbwT.iMebA/SfPfkFqgJfU2cS',
            'firstname' => self::faker()->firstName,
            'lastname' => self::faker()->lastName,
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
