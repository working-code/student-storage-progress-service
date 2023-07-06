<?php

namespace App\Tests\unit\Manager;

use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Manager\CourseManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\MockInterface;

class CourseManagerTest extends Unit
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    private static EntityManagerInterface|MockInterface $em;

    public static function setUpBeforeClass(): void
    {
        static::$em = Mockery::mock(EntityManagerInterface::class);
    }

    public function courseDataProvider(): array
    {
        return [
            'all data' => [
                'title' => 'Symfony Framework',
                'content' => 'Symfony — один из самых сложных, надежных и высокопроизводительных PHP-фреймворков. Он по праву считается основным фреймворком для решения задач уровня enterprise. Symfony используют многие популярные проекты, например, Drupal, phpBB. Даже самый популярный PHP-фреймворк Laravel построен на основе Symfony. Курс рассчитан на PHP-разработчиков с опытом работы от двух лет.',
            ],
            'empty title' => [
                'title' => '',
                'content' => 'Уникальные знания = большие возможности. Успей прокачаться и занять вакантное место в большом проекте!',
            ],
            'empty content' => [
                'title' => 'Java Developer. Professional',
                'content' => ''
            ],
            'empty data' => [
                'title' => '',
                'content' => ''
            ],
        ];
    }

    /**
     * @dataProvider courseDataProvider
     */
    public function testCreate(string $title, string $content): void
    {
        static::$em->shouldReceive('persist')->once();

        $courseManager = new CourseManager(static::$em);
        $course = $courseManager->create($title, $content);

        self::assertEquals($title, $course->getTitle());
        self::assertEquals($content, $course->getContent());
        self::assertEquals(TaskType::Course, $course->getType());
    }

    /**
     * @dataProvider courseDataProvider
     */
    public function testUpdate(string $title, string $content): void
    {
        $course = new Task();
        $courseManager = new CourseManager(static::$em);
        $courseManager->update($course, $title, $content);

        self::assertEquals($title, $course->getTitle());
        self::assertEquals($content, $course->getContent());
    }

    public function testDelete(): void
    {
        static::$em->shouldReceive('remove')->once();

        $courseManager = new CourseManager(static::$em);
        $courseManager->delete((new Task()));
    }

    public function testEmFlush(): void
    {
        static::$em->shouldReceive('flush')->once();

        $courseManager = new CourseManager(static::$em);
        $courseManager->emFlush();
    }
}
