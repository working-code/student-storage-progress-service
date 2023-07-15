<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\UserAchievement;
use App\Entity\UserCourse;
use App\Manager\UserAchievementManager;
use App\Repository\UserAchievementRepository;
use DateTime;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class UserAchievementService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CourseService          $courseService,
        private readonly TaskAssessmentService  $taskAssessmentService,
        private readonly AchievementService     $achievementService,
        private readonly UserAchievementManager $userAchievementManager,
    )
    {
    }

    public function getUserAchievementsWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var UserAchievementRepository $userAchievementRepository */
        $userAchievementRepository = $this->em->getRepository(UserAchievement::class);

        return $userAchievementRepository->getUserAchievementsWithOffset($numberPage, $countInPage);
    }

    /**
     * @throws Exception
     */
    public function issuanceOfAchievement(DateTime $month): void
    {
        $numberPage = 1;
        $countInPage = 10;

        while ($courses = $this->courseService->getCourseWithOffset($numberPage, $countInPage)) {
            foreach ($courses as $course) {
                $this->issuanceOfAchievementByCourse($course, $month);
            }

            $numberPage++;
        }
    }

    /**
     * @throws Exception
     */
    public function issuanceOfAchievementByCourse(Task $course, DateTime $month): void
    {
        $students = $course->getUserCourses()->map(static fn(UserCourse $userCourse) => $userCourse->getUser());
        $minAssessments = $this->taskAssessmentService
            ->getMinAssessmentByCourseAndStudentsByMonth($course, $students, $month);

        $studentsList = array_combine(
            $students->map(static fn(User $user) => $user->getId())->toArray(),
            $students->toArray()
        );

        foreach ($minAssessments as $assessment) {
            if ($assessment['assessment'] === 10) {
                $this->userAchievementManager->create(
                    $studentsList[$assessment['user_id']],
                    $this->achievementService->getAchievementSuperGold()
                );
            } elseif ($assessment['assessment'] >= 9) {
                $this->userAchievementManager->create(
                    $studentsList[$assessment['user_id']],
                    $this->achievementService->getAchievementSuperSilver()
                );
            }
        }

        $this->userAchievementManager->emFlush();
    }
}
