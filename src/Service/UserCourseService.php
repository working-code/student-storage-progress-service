<?php

namespace App\Service;

use App\DTO\UserCourseDTO;
use App\Entity\UserCourse;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use App\Manager\UserCourseManager;
use App\Repository\UserCourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserCourseService
{
    public function __construct(
        private readonly UserCourseManager      $userCourseManager,
        private readonly ValidatorInterface     $validator,
        private readonly UserService            $userService,
        private readonly CourseService          $courseService,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function createUserCourseFromUserCourseDTO(UserCourseDTO $userCourseDTO): UserCourse
    {
        $user = $this->userService->findUserById($userCourseDTO->getUserId());
        $course = $this->courseService->findCourseById($userCourseDTO->getCourseId());

        if (!$user) {
            throw new NotFoundException('not found user with id = ' . $userCourseDTO->getUserId());
        }

        if (!$course) {
            throw new NotFoundException('not found course with id = ' . $userCourseDTO->getCourseId());
        }

        $userCourse = $this->userCourseManager->create($user, $course);
        $this->checkExistErrorsValidation($userCourse);
        $this->userCourseManager->emFlush();

        return $userCourse;
    }

    /**
     * @throws ValidationException
     */
    private function checkExistErrorsValidation(UserCourse $userCourse): void
    {
        $errors = $this->validator->validate($userCourse);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function updateUserCourseFromUserCourseDTO(UserCourse $userCourse, UserCourseDTO $userCourseDTO): UserCourse
    {
        $user = $this->userService->findUserById($userCourseDTO->getUserId());
        $course = $this->courseService->findCourseById($userCourseDTO->getCourseId());

        if (!$user) {
            throw new NotFoundException('not found user with id = ' . $userCourseDTO->getUserId());
        }

        if (!$course) {
            throw new NotFoundException('not found course with id = ' . $userCourseDTO->getCourseId());
        }

        $userCourse
            ->setUser($user)
            ->setCourse($course);

        $this->checkExistErrorsValidation($userCourse);
        $this->userCourseManager->emFlush();

        return $userCourse;
    }

    /**
     * @return UserCourse[]
     */
    public function getUserCourseWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var UserCourseRepository $userCourseRepository */
        $userCourseRepository = $this->em->getRepository(UserCourse::class);

        return $userCourseRepository->getUserCourseWithOffset($numberPage, $countInPage);
    }
}
