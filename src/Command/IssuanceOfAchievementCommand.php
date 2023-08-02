<?php

namespace App\Command;

use App\Service\CourseService;
use App\Service\UserAchievementService;
use DateTime;
use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IssuanceOfAchievementCommand extends Command
{
    public function __construct(
        private readonly UserAchievementService $userAchievementService,
        private readonly CourseService          $courseService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app:achievement:issuance-of')
            ->setDescription('Issuance of achievement student for said month')
            ->addArgument('date', InputArgument::REQUIRED, 'month format d.m.Y')
            ->addArgument('courseId', InputArgument::OPTIONAL, 'only by courseId');
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = new DateTime($input->getArgument('date'));
        $courseId = (int)$input->getArgument('courseId');


        if ($courseId) {
            $course = $this->courseService->findCourseById($courseId);

            if (!$course) {
                $output->writeln('course not found');

                return static::FAILURE;
            }

            $this->userAchievementService->issuanceOfAchievementByCourse($course, $date);
        } else {
            $this->userAchievementService->issuanceOfAchievement($date);
        }

        return static::SUCCESS;
    }
}
