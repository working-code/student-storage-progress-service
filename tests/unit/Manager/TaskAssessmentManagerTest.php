<?php

namespace App\Tests\unit\Manager;

use App\Entity\Task;
use App\Entity\TaskAssessment;
use App\Entity\User;
use App\Manager\TaskAssessmentManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class TaskAssessmentManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function taskAssessmentDataProvider(): array
    {
        return [
            'all data' => ['task' => new Task(), 'assessment' => 10, 'user' => new User()],
            'assessment zero' => ['task' => new Task(), 'assessment' => 0, 'user' => new User()],
        ];
    }

    /**
     * @dataProvider taskAssessmentDataProvider
     */
    public function testCreate(Task $task, int $assessment, User $user): void
    {
        static::$em->shouldReceive('persist')->once();

        $taskAssessmentManager = new TaskAssessmentManager(static::$em);
        $taskAssessment = $taskAssessmentManager->create($task, $assessment, $user);

        self::assertEquals($task, $taskAssessment->getTask());
        self::assertEquals($assessment, $taskAssessment->getAssessment());
        self::assertEquals($user, $taskAssessment->getUser());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $taskAssessmentManager = new TaskAssessmentManager(static::$em);
        $taskAssessmentManager->delete((new TaskAssessment()));
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $taskAssessmentManager = new TaskAssessmentManager(static::$em);
        $taskAssessmentManager->emFlush();
    }
}
