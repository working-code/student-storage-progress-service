<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class UserAchievementDTO
{
    private ?int $userId;

    #[SerializedName('achievement')]
    #[Assert\Valid]
    private ?AchievementDTO $achievementDTO;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getAchievementDTO(): ?AchievementDTO
    {
        return $this->achievementDTO;
    }

    public function setAchievementDTO(?AchievementDTO $achievementDTO): self
    {
        $this->achievementDTO = $achievementDTO;

        return $this;
    }
}
