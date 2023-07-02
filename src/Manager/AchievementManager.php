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
        $achievement = (new Achievement())
            ->setTitle($title)
            ->setDescription($description);

        $this->em->persist($achievement);

        return $achievement;
    }

    public function update(Achievement $achievement, string $title, string $description): void
    {
        $achievement->setTitle($title)
            ->setDescription($description);
    }

    public function delete(Achievement $achievement): self
    {
        $this->em->remove($achievement);

        return $this;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }
}
