<?php

namespace App\Tests\unit\Manager;

use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Manager\LessonManager;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class LessonManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function lessonDataProvider(): array
    {
        return [
            'all data' => [
                'title' => 'Doctrine ORM',
                'content' => 'познакомиться с Doctrine; создать базовые модели; разобрать связи между моделями.'
            ],
            'empty title' => [
                'title' => '',
                'content' => 'работать с маршрутизацией в Symfony; настраивать методы обработки пользовательских запросов; конфигурировать контроллеры с помощью SensioFrameworkExtraBundle.'
            ],
            'empty content' => [
                'title' => 'Symfony Forms',
                'content' => ''
            ],
            'empty data' => [
                'title' => '',
                'content' => ''
            ],
        ];
    }

    /**
     * @dataProvider lessonDataProvider
     */
    public function testCreate(string $title, string $content): void
    {
        static::$em->shouldReceive('persist')->once();

        $lessonManager = new LessonManager(static::$em);
        $lesson = $lessonManager->create($title, $content);

        self::assertEquals($title, $lesson->getTitle());
        self::assertEquals($content, $lesson->getContent());
        self::assertEquals(TaskType::Lesson, $lesson->getType());
    }

    /**
     * @dataProvider lessonDataProvider
     */
    public function testUpdate(string $title, string $content): void
    {
        $lesson = new Task();
        $lessonManager = new LessonManager(static::$em);
        $lessonManager->update($lesson, $title, $content);

        self::assertEquals($title, $lesson->getTitle());
        self::assertEquals($content, $lesson->getContent());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $lessonManager = new LessonManager(static::$em);
        $lessonManager->delete((new Task()));
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $lessonManager = new LessonManager(static::$em);
        $lessonManager->emFlush();
    }
}
