<?php

namespace App\DTO\Builder;

use App\DTO\AchievementDTO;
use App\Entity\Achievement;

class AchievementDTOBuilder
{
    public function buildFromEntity(Achievement $achievement): AchievementDTO
    {
        return (new AchievementDTO())
            ->setId($achievement->getId())
            ->setTitle($achievement->getTitle())
            ->setDescription($achievement->getDescription());
    }
}
