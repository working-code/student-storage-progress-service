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
        return (new UserAchievement())
            ->setUser($user)
            ->setAchievement($achievement);
    }

    public function save(UserAchievement $userAchievement): UserAchievement
    {
        $this->em->persist($userAchievement);
        $this->em->flush();

        return $userAchievement;
    }

    public function update(UserAchievement $userAchievement): UserAchievement
    {
        $this->em->flush();

        return $userAchievement;
    }

    public function delete(UserAchievement $userAchievement): void
    {
        $this->em->remove($userAchievement);
        $this->em->flush();
    }
}
