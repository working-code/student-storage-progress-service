<?php

namespace App\Tests\unit\Manager;

use App\Entity\Skill;
use App\Manager\SkillManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class SkillManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function skillDataProvider(): array
    {
        return [
            'all data' => ['name' => 'Symfony. Создание CRUD контроллеров'],
            'empty data' => ['name' => ''],
        ];
    }

    /**
     * @dataProvider skillDataProvider
     */
    public function testCreate(string $name): void
    {
        static::$em->shouldReceive('persist')->once();

        $skillManager = new SkillManager(static::$em);
        $skill = $skillManager->create($name);

        self::assertEquals($name, $skill->getName());
    }

    /**
     * @dataProvider skillDataProvider
     */
    public function testUpdate(string $name): void
    {
        $skill = new Skill();
        $skillManager = new SkillManager(static::$em);
        $skillManager->update($skill, $name);

        self::assertEquals($name, $skill->getName());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $skillManager = new SkillManager(static::$em);
        $skillManager->delete(new Skill());
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $skillManager = new SkillManager(static::$em);
        $skillManager->emFlush();
    }
}
