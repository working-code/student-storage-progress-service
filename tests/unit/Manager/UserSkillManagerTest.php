<?php

namespace App\Tests\unit\Manager;

use App\Entity\Skill;
use App\Entity\User;
use App\Entity\UserSkill;
use App\Manager\UserSkillManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class UserSkillManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function userSkillDataProvider(): array
    {
        return [
            'all data' => ['user' => new User(), 'skill' => new Skill(), 'value' => 3],
            'zero value' => ['user' => new User(), 'skill' => new Skill(), 'value' => 0],
        ];
    }

    /**
     * @dataProvider userSkillDataProvider
     */
    public function testCreate(User $user, Skill $skill, int $value): void
    {
        static::$em->shouldReceive('persist')->once();

        $userSkillManager = new UserSkillManager(static::$em);
        $userSkill = $userSkillManager->create($user, $skill, $value);

        self::assertEquals($user, $userSkill->getUser());
        self::assertEquals($skill, $userSkill->getSkill());
        self::assertEquals($value, $userSkill->getValue());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $userSkillManager = new UserSkillManager(static::$em);
        $userSkillManager->delete((new UserSkill()));
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $userSkillManager = new UserSkillManager(static::$em);
        $userSkillManager->emFlush();
    }
}
