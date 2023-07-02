<?php

namespace App\Tests\unit\Manager;

use App\Entity\User;
use App\Manager\UserManager;
use App\Security\UserRole;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class UserManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function userDataProvider(): array
    {
        return [
            'all data' => [
                'surname' => 'Кисель',
                'name' => 'Юрий',
                'patronymic' => 'Павлович',
                'email' => 'student@php.ru',
                'roles' => [UserRole::VIEW],
                'password' => 'password1',
            ],
            'empty data' => [
                'surname' => '',
                'name' => '',
                'patronymic' => '',
                'email' => '',
                'roles' => [],
                'password' => '',
            ],
            'part empty data' => [
                'surname' => 'Умный',
                'name' => 'Олег',
                'patronymic' => '',
                'email' => 'teacher@php.ru',
                'roles' => [UserRole::TEACHER],
                'password' => '',
            ],
        ];
    }

    /**
     * @dataProvider userDataProvider
     */
    public function testCreate(
        string $surname,
        string $name,
        string $patronymic,
        string $email,
        array  $roles,
        string $password,
    ): void
    {
        static::$em->shouldReceive('persist')->once();

        $userManager = new UserManager(static::$em);
        $user = $userManager->create($surname, $name, $patronymic, $email, $roles, $password);

        self::assertEquals($surname, $user->getSurname());
        self::assertEquals($name, $user->getName());
        self::assertEquals($patronymic, $user->getPatronymic());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($this->getRolesForUser($roles), $user->getRoles());
        self::assertEquals($password, $user->getPassword());
    }

    private function getRolesForUser(array $roles): array
    {
        $roles[] = UserRole::VIEW;

        return array_unique($roles);
    }

    /**
     * @dataProvider userDataProvider
     */
    public function testUpdate(
        string $surname,
        string $name,
        string $patronymic,
        string $email,
        array  $roles,
        string $password,
    ): void
    {
        $user = new User();
        $userManager = new UserManager(static::$em);
        $userManager->update($user, $surname, $name, $patronymic, $email, $roles, $password);

        self::assertEquals($surname, $user->getSurname());
        self::assertEquals($name, $user->getName());
        self::assertEquals($patronymic, $user->getPatronymic());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($this->getRolesForUser($roles), $user->getRoles());
        self::assertEquals($password, $user->getPassword());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $userManager = new UserManager(static::$em);
        $userManager->delete((new User()));
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $userManager = new UserManager(static::$em);
        $userManager->emFlush();
    }
}
