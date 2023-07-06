<?php

namespace App\Tests\functional\Service;

use App\DTO\Builder\LessonWrapperDTOBuilder;
use App\Entity\Enums\TaskType;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Service\LessonService;
use App\Tests\FunctionalTester;
use Codeception\Example;

class LessonServiceCest
{
    public function lessonDataProvider(): array
    {
        return [
            'correct data' => [
                'lessonDTO' => [
                    'lesson' => [
                        'title' => 'Контроллеры и маршрутизация',
                        'content' => 'работать с маршрутизацией в Symfony; настраивать методы обработки пользовательских запросов; конфигурировать контроллеры с помощью SensioFrameworkExtraBundle.'
                    ],
                    'taskIds' => [4, 5]
                ],
                'exception' => false,
            ],
            'empty title' => [
                'lessonDTO' => [
                    'lesson' => [
                        'title' => '',
                        'content' => 'работать с маршрутизацией в Symfony; настраивать методы обработки пользовательских запросов; конфигурировать контроллеры с помощью SensioFrameworkExtraBundle.'
                    ],
                    'taskIds' => [4, 5]
                ],
                'exception' => true,
            ],
            'empty content' => [
                'lessonDTO' => [
                    'lesson' => [
                        'title' => 'Контроллеры и маршрутизация',
                        'content' => ''
                    ],
                    'taskIds' => [4, 5]
                ],
                'exception' => true,
            ],
            'empty task' => [
                'lessonDTO' => [
                    'lesson' => [
                        'title' => 'Контроллеры и маршрутизация',
                        'content' => 'работать с маршрутизацией в Symfony; настраивать методы обработки пользовательских запросов; конфигурировать контроллеры с помощью SensioFrameworkExtraBundle.'
                    ],
                    'taskIds' => [],
                ],
                'exception' => false,
            ],
            'incorrect task' => [
                'lessonDTO' => [
                    'lesson' => [
                        'title' => 'Контроллеры и маршрутизация',
                        'content' => 'работать с маршрутизацией в Symfony; настраивать методы обработки пользовательских запросов; конфигурировать контроллеры с помощью SensioFrameworkExtraBundle.'
                    ],
                    'taskIds' => [33],
                ],
                'exception' => false,
            ],
            'empty all data' => [
                'lessonDTO' => [
                    'lesson' => [
                        'title' => '',
                        'content' => ''
                    ],
                    'taskIds' => [],
                ],
                'exception' => true,
            ],
            'empty lesson' => [
                'lessonDTO' => [
                    'lesson' => [
                        'title' => '',
                        'content' => ''
                    ],
                    'taskIds' => [4, 5],
                ],
                'exception' => true,
            ],
        ];
    }

    /**
     * @dataProvider lessonDataProvider
     * @throws ValidationException
     */
    public function testCreateLessonFromLessonWrapperDTO(FunctionalTester $I, Example $example): void
    {
        /** @var LessonWrapperDTOBuilder $lessonWrapperDTOBuilder */
        $lessonWrapperDTOBuilder = $I->grabService(LessonWrapperDTOBuilder::class);
        $lessonDTOWrapper = $lessonWrapperDTOBuilder->buildFromArray($example['lessonDTO']);

        /** @var LessonService $lessonService */
        $lessonService = $I->grabService(LessonService::class);

        if ($example['exception']) {
            $I->expectThrowable(ValidationException::class, static function () use ($lessonService, $lessonDTOWrapper) {
                $lessonService->createLessonFromLessonWrapperDTO($lessonDTOWrapper);
            });
        } else {
            $lesson = $lessonService->createLessonFromLessonWrapperDTO($lessonDTOWrapper);

            $I->assertSame($example['lessonDTO']['lesson']['title'], $lesson->getTitle());
            $I->assertSame($example['lessonDTO']['lesson']['content'], $lesson->getContent());
            $I->assertSame(TaskType::Lesson, $lesson->getType());
            $I->assertTrue(count($example['lessonDTO']['taskIds']) >= $lesson->getChildren()->count());
        }
    }

    /**
     * @dataProvider lessonDataProvider
     * @throws ValidationException
     */
    public function testUpdateLessonFromLessonWrapperDTO(FunctionalTester $I, Example $example): void
    {
        /** @var LessonWrapperDTOBuilder $lessonWrapperDTOBuilder */
        $lessonWrapperDTOBuilder = $I->grabService(LessonWrapperDTOBuilder::class);
        $lessonDTOWrapper = $lessonWrapperDTOBuilder->buildFromArray($example['lessonDTO']);

        /** @var LessonService $lessonService */
        $lessonService = $I->grabService(LessonService::class);

        $lesson = $I->have(Task::class, ['type' => TaskType::Lesson]);

        if ($example['exception']) {
            $I->expectThrowable(
                ValidationException::class,
                static function () use ($lesson, $lessonService, $lessonDTOWrapper) {
                    $lessonService->updateLessonFromLessonWrapperDTO($lesson, $lessonDTOWrapper);
                });
        } else {
            $lesson = $lessonService->updateLessonFromLessonWrapperDTO($lesson, $lessonDTOWrapper);

            $I->assertSame($example['lessonDTO']['lesson']['title'], $lesson->getTitle());
            $I->assertSame($example['lessonDTO']['lesson']['content'], $lesson->getContent());
            $I->assertSame(TaskType::Lesson, $lesson->getType());
            $I->assertTrue(count($example['lessonDTO']['taskIds']) >= $lesson->getChildren()->count());
        }
    }
}
