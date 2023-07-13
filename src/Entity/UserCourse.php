<?php

namespace App\Entity;

use App\Repository\UserCourseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'user_course')]
#[ORM\Entity(repositoryClass: UserCourseRepository::class)]
#[ORM\Index(columns: ['user_id'], name: 'user_course__user_id__ind')]
#[ORM\Index(columns: ['course_id'], name: 'user_course__course_id__ind')]
class UserCourse
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userCourses')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotBlank]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'userCourses')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotBlank]
    private Task $course;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCourse(): Task
    {
        return $this->course;
    }

    public function setCourse(Task $course): self
    {
        $this->course = $course;

        return $this;
    }
}
