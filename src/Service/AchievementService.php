<?php

namespace App\Service;

use App\Entity\Achievement;
use App\Repository\AchievementRepository;
use Doctrine\ORM\EntityManagerInterface;

class AchievementService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    /**
     * @return Achievement[]
     */
    public function getAchievementsWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var AchievementRepository $achievementRepository */
        $achievementRepository = $this->em->getRepository(Achievement::class);

        return $achievementRepository->getAchievementWithOffset($numberPage, $countInPage);
    }
}
