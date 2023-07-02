<?php

namespace App\Tests\unit\Manager;

use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Manager\TaskManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class TaskManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function taskDataProvider(): array
    {
        return [
            'all data' => [
                'title' => 'подготовить Entity-классы',
                'content' => 'В работе должны быть описаны основные сущности бизнес-логики из проекта. Проверить валидность описания можно командой doctrine:schema:validate'
            ],
            'empty title' => [
                'title' => '',
                'content' => 'Для всех сущностей должны присутствовать классы репозиториев с методами поиска, которые могут понадобиться в проекте (помимо встроенных методов поиска по произвольному набору полей)'
            ],
            'empty content' => [
                'title' => 'подготовить миграции БД',
                'content' => ''
            ],
            'empty data' => [
                'title' => '',
                'content' => ''
            ],
        ];
    }

    /**
     * @dataProvider taskDataProvider
     */
    public function testCreate(string $title, string $content): void
    {
        static::$em->shouldReceive('persist')->once();

        $taskManager = new TaskManager(static::$em);
        $task = $taskManager->create($title, $content);

        self::assertEquals($title, $task->getTitle());
        self::assertEquals($content, $task->getContent());
        self::assertEquals(TaskType::Task, $task->getType());
    }

    /**
     * @dataProvider taskDataProvider
     */
    public function testUpdate(string $title, string $content): void
    {
        $task = new Task();
        $taskManager = new TaskManager(static::$em);
        $taskManager->update($task, $title, $content);

        self::assertEquals($title, $task->getTitle());
        self::assertEquals($content, $task->getContent());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $taskManager = new TaskManager(static::$em);
        $taskManager->delete((new Task()));
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $taskManager = new TaskManager(static::$em);
        $taskManager->emFlush();
    }
}
