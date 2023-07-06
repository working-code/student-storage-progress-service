<?php

namespace App\Tests\unit\Entity;

use App\Entity\User;
use App\Security\UserRole;
use Codeception\Test\Unit;

class UserTest extends Unit
{
    public function userDataProvider(): array
    {
        return [
            'exceptedAllRole' => [
                'userData' => ['roles' => [UserRole::ADMIN, UserRole::TEACHER, UserRole::VIEW]],
                'checkExistRoles' => [UserRole::ADMIN, UserRole::TEACHER, UserRole::VIEW],
                'excepted' => true,
            ],
            'exceptedNoRole' => [
                'userData' => ['roles' => []],
                'checkExistRoles' => [UserRole::VIEW],
                'excepted' => true,
            ],
            'exceptedRoleAdmin' => [
                'userData' => ['roles' => [UserRole::ADMIN]],
                'checkExistRoles' => [UserRole::VIEW, UserRole::ADMIN],
                'excepted' => true,
            ],
            'exceptedRoleTeacher' => [
                'userData' => ['roles' => [UserRole::TEACHER]],
                'checkExistRoles' => [UserRole::VIEW, UserRole::TEACHER],
                'excepted' => true,
            ],
        ];
    }

    /**
     * @dataProvider userDataProvider
     */
    public function testExistRoles(array $userData, array $checkExistRoles, bool $excepted): void
    {
        $user = $this->createUser($userData);

        foreach ($checkExistRoles as $role) {
            static::assertSame($excepted, $user->hasRole($role), 'return correct result');
        }
    }

    private function createUser(array $userData): User
    {
        return (new User())
            ->setRoles($userData['roles']);
    }
}
