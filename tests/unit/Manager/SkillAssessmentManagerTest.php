<?php

namespace App\Tests\unit\Manager;

use App\Entity\Skill;
use App\Entity\TaskAssessment;
use App\Manager\SkillAssessmentManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class SkillAssessmentManagerTest extends Unit
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
            'all data' => [new Skill(), new TaskAssessment(), 3],
        ];
    }

    /**
     * @dataProvider taskAssessmentDataProvider
     */
    public function testCreate(Skill $skill, TaskAssessment $taskAssessment, int $skillValue): void
    {
        static::$em->shouldReceive('persist')->once();

        $skillAssessmentManager = new SkillAssessmentManager(static::$em);
        $skillAssessment = $skillAssessmentManager->create($skill, $taskAssessment, $skillValue);

        self::assertEquals($skill, $skillAssessment->getSkill());
        self::assertEquals($taskAssessment, $skillAssessment->getTaskAssessment());
        self::assertEquals($skillValue, $skillAssessment->getSkillValue());
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $skillAssessmentManager = new SkillAssessmentManager(static::$em);
        $skillAssessmentManager->emFlush();

    }
}
