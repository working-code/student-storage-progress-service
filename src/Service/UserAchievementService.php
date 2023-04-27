<?php

namespace App\Service;

use App\Entity\UserAchievement;
use App\Repository\UserAchievementRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserAchievementService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function getUserAchievementsWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var UserAchievementRepository $userAchievementRepository */
        $userAchievementRepository = $this->em->getRepository(UserAchievement::class);

        return $userAchievementRepository->getUserAchievementsWithOffset($numberPage, $countInPage);
    }
}
