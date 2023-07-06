<?php

namespace App\Tests\unit\Manager;

use App\Entity\Skill;
use App\Entity\Task;
use App\Entity\TaskSetting;
use App\Manager\TaskSettingManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class TaskSettingManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function taskSettingDataProvider(): array
    {
        return [
            'all data' => ['task' => new Task(), 'skill' => new Skill(), 27],
            'zero value percentage' => ['task' => new Task(), 'skill' => new Skill(), 0],
        ];
    }

    /**
     * @dataProvider taskSettingDataProvider
     */
    public function testCreate(Task $task, Skill $skill, int $value): void
    {
        static::$em->shouldReceive('persist')->once();

        $taskSettingManager = new TaskSettingManager(static::$em);
        $taskSetting = $taskSettingManager->create($task, $skill, $value);

        self::assertEquals($task, $taskSetting->getTask());
        self::assertEquals($skill, $taskSetting->getSkill());
        self::assertEquals($value, $taskSetting->getValuePercentage());
    }

    /**
     * @dataProvider taskSettingDataProvider
     */
    public function testUpdate(Task $task, Skill $skill, int $value): void
    {
        $taskSetting = new TaskSetting();
        $taskSettingManager = new TaskSettingManager(static::$em);
        $taskSettingManager->update($taskSetting, $task, $skill, $value);

        self::assertEquals($task, $taskSetting->getTask());
        self::assertEquals($skill, $taskSetting->getSkill());
        self::assertEquals($value, $taskSetting->getValuePercentage());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $taskSettingManager = new TaskSettingManager(static::$em);
        $taskSettingManager->delete((new TaskSetting()));
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $taskSettingManager = new TaskSettingManager(static::$em);
        $taskSettingManager->emFlush();
    }
}
