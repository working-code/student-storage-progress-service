<?php

namespace App\Manager;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\UserCourse;
use Doctrine\ORM\EntityManagerInterface;

class UserCourseManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(User $user, Task $course): UserCourse
    {
        $userCourse = (new UserCourse())
            ->setUser($user)
            ->setCourse($course);

        $this->em->persist($userCourse);

        return $userCourse;
    }

    public function delete(UserCourse $userCourse): self
    {
        $this->em->remove($userCourse);

        return $this;
    }

    public function emFlush(): void
    {
        $this->em->flush();
    }
}
