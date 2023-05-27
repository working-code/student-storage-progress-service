<?php

namespace App\DTO\Builder;

use App\DTO\UserAchievementDTO;
use App\Entity\UserAchievement;

class UserAchievementDTOBuilder
{
    public function __construct(private readonly AchievementDTOBuilder $achievementDTOBuilder)
    {
    }

    public function buildFromEntity(UserAchievement $userAchievement): UserAchievementDTO
    {
        $userAchievementDTO = (new UserAchievementDTO())
            ->setUserId($userAchievement->getUser()?->getId());

        if ($userAchievement->getAchievement()) {
            $userAchievementDTO->setAchievementDTO(
                $this->achievementDTOBuilder->buildFromEntity($userAchievement->getAchievement())
            );
        }

        return $userAchievementDTO;
    }
}
