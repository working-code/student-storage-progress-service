<?php

namespace App\Tests\acceptance\Api\v1;

use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Tests\AcceptanceTester;
use Codeception\Util\HttpCode;

class TaskControllerCest
{
    private const BASE_URL = '/api/v1/task';

    private const TASK_1 = [
        'title' => 'подготовить Entity-классы',
        'content' => 'В работе должны быть описаны основные сущности бизнес-логики из проекта. Проверить валидность описания можно командой doctrine:schema:validate',
        'type' => TaskType::Task
    ];
    private const TASK_2 = [
        'title' => 'подготовить Repository-классы',
        'content' => 'Для всех сущностей должны присутствовать классы репозиториев с методами поиска, которые могут понадобиться в проекте (помимо встроенных методов поиска по произвольному набору полей)',
    ];

    public function testShowAdmin(AcceptanceTester $I): void
    {
        $task = $I->have(Task::class, self::TASK_1);
        $I->authForUser('admin');
        $I->sendGet(self::BASE_URL . '/' . $task->getId());
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'task' => [
                'id' => 'integer',
                'title' => 'string',
                'content' => 'string',
            ]
        ]);
    }

    public function testShowAdminNotFound(AcceptanceTester $I): void
    {
        $I->authForUser('admin');
        $I->sendGet(self::BASE_URL . '/0');
        $I->canSeeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'description' => 'string',
        ]);
    }

    public function testShowNoAuth(AcceptanceTester $I): void
    {
        $task = $I->have(Task::class, self::TASK_1);
        $I->sendGet(self::BASE_URL . '/' . $task->getId());
        $I->canSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'description' => 'string',
        ]);
    }

    public function testStoreAdmin(AcceptanceTester $I): void
    {
        $I->authForUser('admin');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(self::BASE_URL, ['task' => self::TASK_2]);
        $I->canSeeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'task' => [
                'id' => 'integer',
                'title' => 'string',
                'content' => 'string',
            ]
        ]);
    }

    public function testStoreAdminIncorrect(AcceptanceTester $I): void
    {
        $I->authForUser('admin');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost(self::BASE_URL, self::TASK_2);
        $I->canSeeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'title' => 'string',
            'errors' => 'array',
        ]);
    }
}
