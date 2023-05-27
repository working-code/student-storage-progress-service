<?php

namespace App\Controller\Api\v1;

use App\DTO\Builder\UserAchievementDTOBuilder;
use App\Entity\UserAchievement;
use App\Service\UserAchievementService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/user_achievement')]
class UserAchievementController extends BaseController
{
    public function __construct(
        private readonly UserAchievementService    $userAchievementService,
        private readonly UserAchievementDTOBuilder $userAchievementDTOBuilder,
    )
    {
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $numberPage = $request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);
        $countInPage = $request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);

        $userAchievements = $this->userAchievementService->getUserAchievementsWithOffset($numberPage, $countInPage);
        $data = ['userAchievements' => array_map(
            fn(UserAchievement $userAchievement) => $this->userAchievementDTOBuilder->buildFromEntity($userAchievement),
            $userAchievements
        )];

        return $this->json($data, $userAchievements ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
