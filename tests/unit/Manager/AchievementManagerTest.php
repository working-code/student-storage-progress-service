<?php

namespace App\Tests\unit\Manager;

use App\Entity\Achievement;
use App\Manager\AchievementManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class AchievementManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function achievementForCreateDataProvider(): array
    {
        return [
            'Super gold' => [
                'title' => 'Супер золото',
                'description' => 'Сдано домашние задание 9 баллов',
            ],
            'Gold' => [
                'title' => 'Золото',
                'description' => 'Сдано домашние задание на 10 баллов',
            ],
            'Super silver' => [
                'title' => 'Супер серебро',
                'description' => 'Все домашние задания сданы на 9 баллов',
            ],
            'Silver' => [
                'title' => 'Серебро',
                'description' => 'Все домашние задания сданы на 10 баллов',
            ],
        ];
    }

    /**
     * @dataProvider achievementForCreateDataProvider
     */
    public function testCreate(string $title, string $description): void
    {
        static::$em->shouldReceive('persist')->once();

        $achievement = $this->createAchievement($title, $description);

        self::assertEquals($title, $achievement->getTitle());
        self::assertEquals($description, $achievement->getDescription());
    }

    private function createAchievement(string $title, string $description): Achievement
    {
        $achievementManager = new AchievementManager(static::$em);

        return $achievementManager->create($title, $description);
    }

    public function achievementForUpdateDataProvider(): array
    {
        return [
            'all data' => [
                'title' => 'Супер золото',
                'description' => 'Сдано домашние задание 9 баллов',
            ],
            'only title' => [
                'title' => 'Золото',
                'description' => '',
            ],
            'only description' => [
                'title' => '',
                'description' => 'Все домашние задания сданы на 9 баллов',
            ],
            'empty data' => [
                'title' => '',
                'description' => '',
            ],
        ];
    }

    /**
     * @dataProvider achievementForUpdateDataProvider
     */
    public function testUpdate(string|null $title, string|null $description): void
    {
        $achievementManager = new AchievementManager(static::$em);
        $achievement = new Achievement();
        $achievementManager->update($achievement, $title, $description);

        self::assertSame($title, $achievement->getTitle());
        self::assertSame($description, $achievement->getDescription());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $achievementManager = new AchievementManager(static::$em);
        $achievementManager->delete((new Achievement()));
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $achievementManager = new AchievementManager(static::$em);
        $achievementManager->emFlush();
    }
}
