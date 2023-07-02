<?php

namespace App\Tests\functional\Service;

use App\DTO\Builder\UserDTOBuilder;
use App\Entity\User;
use App\Exception\ValidationException;
use App\Security\UserRole;
use App\Service\AuthService;
use App\Service\UserService;
use App\Tests\FunctionalTester;
use Codeception\Example;

class UserServiceCest
{
    public function userDataProvider(): array
    {
        return [
            'user correct data' => [
                'user' => [
                    'surname' => 'Дуб',
                    'name' => 'Андрей',
                    'patronymic' => 'Иванович',
                    'email' => 'admin777@php.ru',
                    'roles' => ['ROLE_ADMIN'],
                    'password' => 'password',
                ],
                'exception' => false,
            ],
            'incorrect email' => [
                'user' => [
                    'surname' => 'Дуб',
                    'name' => 'Андрей',
                    'patronymic' => 'Иванович',
                    'email' => 'adminphp.ru',
                    'roles' => ['ROLE_ADMIN'],
                    'password' => 'password',
                ],
                'exception' => true,
            ],
            'empty name' => [
                'user' => [
                    'surname' => 'Дуб',
                    'name' => '',
                    'patronymic' => 'Иванович',
                    'email' => 'admin777@php.ru',
                    'roles' => ['ROLE_ADMIN'],
                    'password' => 'password',
                ],
                'exception' => true,
            ],
            'empty patronymic' => [
                'user' => [
                    'surname' => 'Дуб',
                    'name' => 'Андрей',
                    'patronymic' => '',
                    'email' => 'admin777@php.ru',
                    'roles' => ['ROLE_ADMIN'],
                    'password' => 'password',
                ],
                'exception' => true,
            ],
            'empty email' => [
                'user' => [
                    'surname' => 'Дуб',
                    'name' => 'Андрей',
                    'patronymic' => 'Иванович',
                    'email' => '',
                    'roles' => ['ROLE_ADMIN'],
                    'password' => 'password',
                ],
                'exception' => true,
            ],
            'empty roles' => [
                'user' => [
                    'surname' => 'Дуб',
                    'name' => 'Андрей',
                    'patronymic' => 'Иванович',
                    'email' => 'admin777@php.ru',
                    'roles' => [],
                    'password' => 'password',
                ],
                'exception' => false,
            ],
            'empty password' => [
                'user' => [
                    'surname' => 'Дуб',
                    'name' => 'Андрей',
                    'patronymic' => 'Иванович',
                    'email' => 'admin777@php.ru',
                    'roles' => ['ROLE_ADMIN'],
                    'password' => '',
                ],
                'exception' => true,
            ],
            'empty all data' => [
                'user' => [
                    'surname' => '',
                    'name' => '',
                    'patronymic' => '',
                    'email' => '',
                    'roles' => [],
                    'password' => '',
                ],
                'exception' => true,
            ],
        ];
    }

    /**
     * @dataProvider userDataProvider
     * @throws ValidationException
     */
    public function testCreateUserFromUserDTO(FunctionalTester $I, Example $example): void
    {
        /** @var UserDTOBuilder $userDTOBuilder */
        $userDTOBuilder = $I->grabService(UserDTOBuilder::class);
        $userDTO = $userDTOBuilder->buildFromArray([
            'surname' => $example['user']['surname'],
            'name' => $example['user']['name'],
            'patronymic' => $example['user']['patronymic'],
            'email' => $example['user']['email'],
            'roles' => $example['user']['roles'],
            'password' => $example['user']['password'],
        ]);

        /** @var UserService $userService */
        $userService = $I->grabService(UserService::class);

        if ($example['exception']) {
            $I->expectThrowable(ValidationException::class, static function () use ($userService, $userDTO) {
                $userService->createUserFromUserDTO($userDTO);
            });
        } else {
            $user = $userService->createUserFromUserDTO($userDTO);

            /** @var AuthService $authService */
            $authService = $I->grabService(AuthService::class);

            $I->assertSame($example['user']['surname'], $user->getSurname());
            $I->assertSame($example['user']['name'], $user->getName());
            $I->assertSame($example['user']['patronymic'], $user->getPatronymic());
            $I->assertSame($example['user']['email'], $user->getEmail());
            $I->assertSame($this->getRolesForUser($example['user']['roles']), $user->getRoles());
            $I->assertSame($example['user']['password'], $user->getPassword());
            $I->assertTrue($authService->isCredentialValid($user, $example['user']['password']));
        }
    }

    private function getRolesForUser(array $roles): array
    {
        $roles[] = UserRole::VIEW;

        return array_unique($roles);
    }

    /**
     * @dataProvider userDataProvider
     * @throws ValidationException
     */
    public function testUpdateUserFromUserDTO(FunctionalTester $I, Example $example): void
    {
        /** @var UserDTOBuilder $userDTOBuilder */
        $userDTOBuilder = $I->grabService(UserDTOBuilder::class);
        $userDTO = $userDTOBuilder->buildFromArray([
            'surname' => $example['user']['surname'],
            'name' => $example['user']['name'],
            'patronymic' => $example['user']['patronymic'],
            'email' => $example['user']['email'],
            'roles' => $example['user']['roles'],
            'password' => $example['user']['password'],
        ]);

        /** @var UserService $userService */
        $userService = $I->grabService(UserService::class);

        $user = $I->have(User::class);

        if ($example['exception']) {
            $I->expectThrowable(ValidationException::class, static function () use ($user, $userService, $userDTO) {
                $userService->updateUserByUserDTO($user, $userDTO);
            });
        } else {
            $userService->updateUserByUserDTO($user, $userDTO);

            /** @var AuthService $authService */
            $authService = $I->grabService(AuthService::class);

            $I->assertSame($example['user']['surname'], $user->getSurname());
            $I->assertSame($example['user']['name'], $user->getName());
            $I->assertSame($example['user']['patronymic'], $user->getPatronymic());
            $I->assertSame($example['user']['email'], $user->getEmail());
            $I->assertSame($this->getRolesForUser($example['user']['roles']), $user->getRoles());
            $I->assertSame($example['user']['password'], $user->getPassword());
            $I->assertTrue($authService->isCredentialValid($user, $example['user']['password']));
        }
    }
}
