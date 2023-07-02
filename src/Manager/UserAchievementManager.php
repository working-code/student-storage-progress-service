<?php

namespace App\Manager;

use App\Entity\Achievement;
use App\Entity\User;
use App\Entity\UserAchievement;
use Doctrine\ORM\EntityManagerInterface;

class UserAchievementManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(User $user, Achievement $achievement): UserAchievement
    {
        $userAchievement = (new UserAchievement())
            ->setUser($user)
            ->setAchievement($achievement);

        $this->em->persist($userAchievement);

        return $userAchievement;
    }

    public function delete(UserAchievement $userAchievement): self
    {
        $this->em->remove($userAchievement);

        return $this;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }
}
