<?php

namespace App\Tests\functional\Service;

use App\DTO\Builder\CourseDTOWrapperBuilder;
use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Service\CourseService;
use App\Tests\FunctionalTester;
use Codeception\Example;

class CourseServiceCest
{
    public function courseDataProvider(): array
    {
        return [
            'correct data' => [
                'courseDTO' => [
                    'course' => [
                        'title' => 'Symfony Framework',
                        'content' => 'Symfony — один из самых сложных, надежных и высокопроизводительных PHP-фреймворков. Он по праву считается основным фреймворком для решения задач уровня enterprise. Symfony используют многие популярные проекты, например, Drupal, phpBB. Даже самый популярный PHP-фреймворк Laravel построен на основе Symfony. Курс рассчитан на PHP-разработчиков с опытом работы от двух лет.'
                    ],
                    'lessonIds' => [1, 2, 3]
                ],
                'exception' => false,
            ],
            'empty title' => [
                'courseDTO' => [
                    'course' => [
                        'title' => '',
                        'content' => 'Symfony — один из самых сложных, надежных и высокопроизводительных PHP-фреймворков. Он по праву считается основным фреймворком для решения задач уровня enterprise. Symfony используют многие популярные проекты, например, Drupal, phpBB. Даже самый популярный PHP-фреймворк Laravel построен на основе Symfony. Курс рассчитан на PHP-разработчиков с опытом работы от двух лет.'
                    ],
                    'lessonIds' => [1, 2, 3]
                ],
                'exception' => true,
            ],
            'empty content' => [
                'courseDTO' => [
                    'course' => [
                        'title' => 'Symfony Framework',
                        'content' => ''
                    ],
                    'lessonIds' => [1, 2, 3]
                ],
                'exception' => true,
            ],
            'empty lesson' => [
                'courseDTO' => [
                    'course' => [
                        'title' => 'Symfony Framework',
                        'content' => 'Symfony — один из самых сложных, надежных и высокопроизводительных PHP-фреймворков. Он по праву считается основным фреймворком для решения задач уровня enterprise. Symfony используют многие популярные проекты, например, Drupal, phpBB. Даже самый популярный PHP-фреймворк Laravel построен на основе Symfony. Курс рассчитан на PHP-разработчиков с опытом работы от двух лет.'
                    ],
                    'lessonIds' => [],
                ],
                'exception' => false,
            ],
            'incorrect lesson' => [
                'courseDTO' => [
                    'course' => [
                        'title' => 'Symfony Framework',
                        'content' => 'Symfony — один из самых сложных, надежных и высокопроизводительных PHP-фреймворков. Он по праву считается основным фреймворком для решения задач уровня enterprise. Symfony используют многие популярные проекты, например, Drupal, phpBB. Даже самый популярный PHP-фреймворк Laravel построен на основе Symfony. Курс рассчитан на PHP-разработчиков с опытом работы от двух лет.'
                    ],
                    'lessonIds' => [7],
                ],
                'exception' => false,
            ],
            'empty all data' => [
                'courseDTO' => [
                    'course' => [
                        'title' => '',
                        'content' => ''
                    ],
                    'lessonIds' => [],
                ],
                'exception' => true,
            ],
            'empty course' => [
                'courseDTO' => [
                    'course' => [
                        'title' => '',
                        'content' => ''
                    ],
                    'lessonIds' => [1, 2, 3],
                ],
                'exception' => true,
            ],
        ];
    }

    /**
     * @dataProvider courseDataProvider
     * @throws ValidationException
     */
    public function testCreateCourseFromCourseWrapperDTO(FunctionalTester $I, Example $example): void
    {
        /** @var CourseDTOWrapperBuilder $courseDTOWrapperBuilder */
        $courseDTOWrapperBuilder = $I->grabService(CourseDTOWrapperBuilder::class);
        $courseDTOWrapper = $courseDTOWrapperBuilder->buildFromArray($example['courseDTO']);

        /** @var CourseService $courseService */
        $courseService = $I->grabService(CourseService::class);

        if ($example['exception']) {
            $I->expectThrowable(ValidationException::class, static function () use ($courseService, $courseDTOWrapper) {
                $courseService->createCourseFromCourseWrapperDTO($courseDTOWrapper);
            });
        } else {
            $course = $courseService->createCourseFromCourseWrapperDTO($courseDTOWrapper);

            $I->assertSame($example['courseDTO']['course']['title'], $course->getTitle());
            $I->assertSame($example['courseDTO']['course']['content'], $course->getContent());
            $I->assertSame(TaskType::Course, $course->getType());
            $I->assertTrue(count($example['courseDTO']['lessonIds']) >= $course->getChildren()->count());
        }
    }

    /**
     * @dataProvider courseDataProvider
     * @throws ValidationException
     */
    public function testUpdateCourseFromCourseWrapperDTO(FunctionalTester $I, Example $example): void
    {
        /** @var CourseDTOWrapperBuilder $courseDTOWrapperBuilder */
        $courseDTOWrapperBuilder = $I->grabService(CourseDTOWrapperBuilder::class);
        $courseDTOWrapper = $courseDTOWrapperBuilder->buildFromArray($example['courseDTO']);

        /** @var CourseService $courseService */
        $courseService = $I->grabService(CourseService::class);

        $course = $I->have(Task::class, ['type' => TaskType::Course]);

        if ($example['exception']) {
            $I->expectThrowable(
                ValidationException::class,
                static function () use ($course, $courseService, $courseDTOWrapper) {
                    $courseService->updateCourseFromCourseWrapperDTO($course, $courseDTOWrapper);
                });
        } else {
            $course = $courseService->updateCourseFromCourseWrapperDTO($course, $courseDTOWrapper);

            $I->assertSame($example['courseDTO']['course']['title'], $course->getTitle());
            $I->assertSame($example['courseDTO']['course']['content'], $course->getContent());
            $I->assertSame(TaskType::Course, $course->getType());
            $I->assertTrue(count($example['courseDTO']['lessonIds']) >= $course->getChildren()->count());
        }
    }
}
