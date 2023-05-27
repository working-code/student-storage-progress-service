<?php

namespace App\Manager;

use App\Entity\Achievement;
use Doctrine\ORM\EntityManagerInterface;

class AchievementManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(string $title, string $description): Achievement
    {
        return (new Achievement())
            ->setTitle($title)
            ->setDescription($description);
    }

    public function save(Achievement $achievement): Achievement
    {
        $this->em->persist($achievement);
        $this->em->flush();

        return $achievement;
    }

    public function update(Achievement $achievement): Achievement
    {
        $this->em->flush();

        return $achievement;
    }

    public function delete(Achievement $achievement): void
    {
        $this->em->remove($achievement);
        $this->em->flush();
    }
}
