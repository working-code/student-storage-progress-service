<?php

namespace App\Controller\Api\v1;

use App\DTO\Builder\AchievementDTOBuilder;
use App\Entity\Achievement;
use App\Service\AchievementService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/achievement')]
class AchievementController extends BaseController
{
    public function __construct(private readonly AchievementDTOBuilder $achievementDTOBuilder)
    {
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[ParamConverter('achievement')]
    public function show(Achievement $achievement): Response
    {
        $achievementDTO = $this->achievementDTOBuilder->buildFromEntity($achievement);

        return $this->json(['achievement' => $achievementDTO], Response::HTTP_OK);
    }

    #[Route(path: '', methods: ['GET'])]
    public function index(Request $request, AchievementService $achievementService): Response
    {
        $numberPage = $request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);
        $countInPage = $request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);

        $achievements = $achievementService->getAchievementsWithOffset($numberPage, $countInPage);
        $data = ['achievements' => array_map(
            fn(Achievement $achievement) => $this->achievementDTOBuilder->buildFromEntity($achievement),
            $achievements
        )];

        return $this->json($data, $data ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
