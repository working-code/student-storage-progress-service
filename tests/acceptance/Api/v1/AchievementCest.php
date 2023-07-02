<?php

namespace App\Tests\acceptance\Api\v1;

use App\Tests\AcceptanceTester;
use Codeception\Example;
use Codeception\Util\HttpCode;

class AchievementCest
{
    private const BASE_URL = '/api/v1/achievement';

    public function showDataProvider(): array
    {
        return [
            'correct show admin' => [
                'user' => 'admin',
                'id' => 1,
                'responseCode' => HttpCode::OK,
                'matchJsonType' => [
                    'achievement' => [
                        'id' => 'integer',
                        'title' => 'string',
                        'description' => 'string',
                    ]
                ]
            ],
            'correct show student' => [
                'user' => 'student',
                'id' => 1,
                'responseCode' => HttpCode::OK,
                'matchJsonType' => [
                    'achievement' => [
                        'id' => 'integer',
                        'title' => 'string',
                        'description' => 'string',
                    ]
                ]
            ],
            'correct show teacher' => [
                'user' => 'teacher',
                'id' => 1,
                'responseCode' => HttpCode::OK,
                'matchJsonType' => [
                    'achievement' => [
                        'id' => 'integer',
                        'title' => 'string',
                        'description' => 'string',
                    ]
                ]
            ],

            'not exists show admin' => [
                'user' => 'admin',
                'id' => 0,
                'responseCode' => HttpCode::NOT_FOUND,
                'matchJsonType' => [
                    'description' => 'string',
                ]
            ],
            'not exists show student' => [
                'user' => 'student',
                'id' => 0,
                'responseCode' => HttpCode::NOT_FOUND,
                'matchJsonType' => [
                    'description' => 'string',
                ]
            ],
            'not exists show teacher' => [
                'user' => 'teacher',
                'id' => 0,
                'responseCode' => HttpCode::NOT_FOUND,
                'matchJsonType' => [
                    'description' => 'string',
                ]
            ],

            'not auth' => [
                'user' => 'not auth',
                'id' => 1,
                'responseCode' => HttpCode::UNAUTHORIZED,
                'matchJsonType' => [
                    'description' => 'string',
                ]
            ],
            'not auth at not exists' => [
                'user' => 'not auth',
                'id' => 0,
                'responseCode' => HttpCode::UNAUTHORIZED,
                'matchJsonType' => [
                    'description' => 'string',
                ]
            ],
        ];
    }

    /** @dataProvider showDataProvider */
    public function testShow(AcceptanceTester $I, Example $example): void
    {
        $I->authForUser($example['user']);
        $I->sendGet(self::BASE_URL . '/' . $example['id']);
        $I->canSeeResponseCodeIs($example['responseCode']);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType($example['matchJsonType']);
    }

    public function indexDataProvider(): array
    {
        return [
            'page 1 count 10' => [
                'user' => 'admin',
                'numberPage' => 1,
                'countInPage' => 10,
                'responseCode' => HttpCode::OK,
                'matchJsonType' => [
                    'achievements' => [
                        [
                            'id' => 'integer',
                            'title' => 'string',
                            'description' => 'string',
                        ]
                    ]
                ]
            ],
            'without parameters' => [
                'user' => 'admin',
                'numberPage' => null,
                'countInPage' => null,
                'responseCode' => HttpCode::OK,
                'matchJsonType' => [
                    'achievements' => [
                        [
                            'id' => 'integer',
                            'title' => 'string',
                            'description' => 'string',
                        ]
                    ]
                ]
            ],
            'no auth' => [
                'user' => 'no auth',
                'numberPage' => 1,
                'countInPage' => 10,
                'responseCode' => HttpCode::UNAUTHORIZED,
                'matchJsonType' => [
                    'description' => 'string',
                ]
            ],
            'not content' => [
                'user' => 'admin',
                'numberPage' => 100,
                'countInPage' => 10,
                'responseCode' => HttpCode::NO_CONTENT,
            ],
        ];
    }

    /**
     * @dataProvider indexDataProvider
     */
    public function testIndex(AcceptanceTester $I, Example $example): void
    {
        $I->authForUser($example['user']);
        $I->sendGet(self::BASE_URL, [
            'numberPage' => $example['numberPage'],
            'countInPage' => $example['countInPage'],
        ]);
        $I->canSeeResponseCodeIs($example['responseCode']);

        if ($example['responseCode'] != HttpCode::NO_CONTENT) {
            $I->seeResponseIsJson();
            $I->seeResponseMatchesJsonType($example['matchJsonType']);
        }

    }
}
