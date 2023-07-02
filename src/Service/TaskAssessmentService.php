<?php

namespace App\Service;

use App\DTO\TaskAssessmentDTO;
use App\Entity\Skill;
use App\Entity\SkillAssessment;
use App\Entity\Task;
use App\Entity\TaskAssessment;
use App\Entity\User;
use App\Event\CreatedTaskAssessmentEvent;
use App\Event\DeletedTaskAssessmentEvent;
use App\Event\UpdatedTaskAssessmentEvent;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use App\Manager\TaskAssessmentManager;
use App\Repository\TaskAssessmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskAssessmentService
{
    public function __construct(
        private readonly TaskService              $taskService,
        private readonly UserService              $userService,
        private readonly TaskAssessmentManager    $taskAssessmentManager,
        private readonly ValidatorInterface       $validator,
        private readonly EntityManagerInterface   $em,
        private readonly EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function createTaskAssessmentFromAssessmentDTO(TaskAssessmentDTO $taskAssessmentDTO): TaskAssessment
    {
        $task = $this->getTaskByTaskAssessmentDTO($taskAssessmentDTO);
        $user = $this->getUserByTaskAssessmentDTO($taskAssessmentDTO);
        $taskAssessment = $this->taskAssessmentManager->create($task, $taskAssessmentDTO->getAssessment(), $user);

        $this->checkExistErrorsValidation($taskAssessment);
        $this->taskAssessmentManager->emFlush();
        $this->eventDispatcher->dispatch(new CreatedTaskAssessmentEvent($taskAssessment));

        return $taskAssessment;
    }

    /**
     * @throws NotFoundException
     */
    private function getTaskByTaskAssessmentDTO(TaskAssessmentDTO $taskAssessmentDTO): Task
    {
        $task = $this->taskService->findTaskById($taskAssessmentDTO->getTaskId());

        if (!$task) {
            throw new NotFoundException();
        }

        return $task;
    }

    /**
     * @throws NotFoundException
     */
    private function getUserByTaskAssessmentDTO(TaskAssessmentDTO $taskAssessmentDTO): User
    {
        $user = $this->userService->findUserById($taskAssessmentDTO->getUserId());

        if (!$user) {
            throw new NotFoundException();
        }

        return $user;
    }

    /**
     * @throws ValidationException
     */
    protected function checkExistErrorsValidation(TaskAssessment $taskAssessment): void
    {
        $errors = $this->validator->validate($taskAssessment);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function updateTaskAssessmentByTaskAssessmentDTO(
        TaskAssessment    $taskAssessment,
        TaskAssessmentDTO $taskAssessmentDTO
    ): TaskAssessment
    {
        $task = $this->getTaskByTaskAssessmentDTO($taskAssessmentDTO);
        $user = $this->getUserByTaskAssessmentDTO($taskAssessmentDTO);
        $oldTaskAssessment = clone $taskAssessment;

        $taskAssessment->setTask($task)
            ->setUser($user)
            ->setAssessment($taskAssessmentDTO->getAssessment());

        $this->checkExistErrorsValidation($taskAssessment);
        $this->taskAssessmentManager->emFlush();
        $this->eventDispatcher->dispatch(new UpdatedTaskAssessmentEvent($taskAssessment, $oldTaskAssessment));

        return $taskAssessment;
    }

    public function getTaskAssessmentWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var TaskAssessmentRepository $taskAssessmentRepository */
        $taskAssessmentRepository = $this->em->getRepository(TaskAssessment::class);

        return $taskAssessmentRepository->getTaskAssessmentWithOffset($numberPage, $countInPage);
    }

    public function deleteTaskAssessment(TaskAssessment $taskAssessment): void
    {
        $deletedTaskAssessmentEvent = new DeletedTaskAssessmentEvent(
            $taskAssessment->getUser(),
            $this->getSkillsByTaskAssessment($taskAssessment)
        );
        $this->taskAssessmentManager->delete($taskAssessment)
            ->emFlush();
        $this->eventDispatcher->dispatch($deletedTaskAssessmentEvent);
    }

    /**
     * @return Skill[]
     */
    public function getSkillsByTaskAssessment(TaskAssessment $taskAssessment): array
    {
        $skills = [];

        foreach ($taskAssessment->getSkillAssessments() as $skillAssessment) {
            /** @var SkillAssessment $skillAssessment */
            $skills[] = $skillAssessment->getSkill();
        }

        return $skills;
    }
}
