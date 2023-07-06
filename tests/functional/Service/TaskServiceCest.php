<?php

namespace App\Tests\functional\Service;

use App\DTO\Builder\TaskDTOBuilder;
use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Service\TaskService;
use App\Tests\FunctionalTester;
use Codeception\Example;

class TaskServiceCest
{
    public function taskDataProvider(): array
    {
        return [
            'correct data' => [
                'task' => [
                    'title' => 'Реализовать CU-действия с этими сущностями с помощью Symfony Forms',
                    'content' => 'Рекомендуем сдать до: 03.04.2023',
                ],
                'exception' => false,
            ],
            'empty title' => [
                'task' => [
                    'title' => '',
                    'content' => 'Рекомендуем сдать до: 03.04.2023',
                ],
                'exception' => true,
            ],
            'empty content' => [
                'task' => [
                    'title' => 'Реализовать CU-действия с этими сущностями с помощью Symfony Forms',
                    'content' => '',
                ],
                'exception' => true,
            ],
            'empty task' => [
                'task' => [
                    'title' => '',
                    'content' => '',
                ],
                'exception' => true,
            ],
        ];
    }

    /**
     * @dataProvider taskDataProvider
     * @throws ValidationException
     */
    public function testCreateTaskFromTaskDTO(FunctionalTester $I, Example $example): void
    {
        /** @var TaskDTOBuilder $taskDTOBuilder */
        $taskDTOBuilder = $I->grabService(TaskDTOBuilder::class);
        $taskDTO = $taskDTOBuilder->buildFromArray($example['task']);

        /** @var TaskService $taskService */
        $taskService = $I->grabService(TaskService::class);

        if ($example['exception']) {
            $I->expectThrowable(ValidationException::class, static function () use ($taskService, $taskDTO) {
                $taskService->createTaskFromTaskDTO($taskDTO);
            });
        } else {
            $task = $taskService->createTaskFromTaskDTO($taskDTO);

            $I->assertSame($example['task']['title'], $task->getTitle());
            $I->assertSame($example['task']['content'], $task->getContent());
            $I->assertSame(TaskType::Task, $task->getType());
        }
    }

    /**
     * @dataProvider taskDataProvider
     * @throws ValidationException
     */
    public function testUpdateTaskFromTaskDTO(FunctionalTester $I, Example $example): void
    {
        /** @var TaskDTOBuilder $taskDTOBuilder */
        $taskDTOBuilder = $I->grabService(TaskDTOBuilder::class);
        $taskDTO = $taskDTOBuilder->buildFromArray($example['task']);

        /** @var TaskService $taskService */
        $taskService = $I->grabService(TaskService::class);

        $task = $I->have(Task::class, ['type' => TaskType::Task]);

        if ($example['exception']) {
            $I->expectThrowable(ValidationException::class, static function () use ($task, $taskService, $taskDTO) {
                $taskService->updateTaskFromTaskDTO($task, $taskDTO);
            });
        } else {
            $task = $taskService->updateTaskFromTaskDTO($task, $taskDTO);

            $I->assertSame($example['task']['title'], $task->getTitle());
            $I->assertSame($example['task']['content'], $task->getContent());
            $I->assertSame(TaskType::Task, $task->getType());
        }
    }
}
