<?php

namespace App\Tests\unit\Manager;

use App\Entity\Achievement;
use App\Entity\User;
use App\Entity\UserAchievement;
use App\Manager\UserAchievementManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class UserAchievementManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function userAchievementDataProvider(): array
    {
        return [
            'all data' => ['user' => new User(), 'achievement' => new Achievement()],
        ];
    }

    /**
     * @dataProvider userAchievementDataProvider
     */
    public function testCreate(User $user, Achievement $achievement): void
    {
        static::$em->shouldReceive('persist')->once();

        $userAchievementManager = new UserAchievementManager(static::$em);
        $userAchievement = $userAchievementManager->create($user, $achievement);

        self::assertEquals($user, $userAchievement->getUser());
        self::assertEquals($achievement, $userAchievement->getAchievement());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $userAchievementManager = new UserAchievementManager(static::$em);
        $userAchievementManager->delete((new UserAchievement()));
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $userAchievementManager = new UserAchievementManager(static::$em);
        $userAchievementManager->emFlush();
    }
}
