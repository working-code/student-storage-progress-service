<?php

namespace App\Service;

use App\DTO\TaskSettingDTO;
use App\Entity\Skill;
use App\Entity\Task;
use App\Entity\TaskSetting;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use App\Manager\TaskSettingManager;
use App\Repository\TaskSettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskSettingService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ValidatorInterface     $validator,
        private readonly TaskService            $taskService,
        private readonly SkillService           $skillService,
        private readonly TaskSettingManager     $taskSettingManager,
    )
    {
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function createTaskSettingFromTaskSettingDTO(TaskSettingDTO $taskSettingDTO): TaskSetting
    {
        $task = $this->taskService->findTaskById($taskSettingDTO->getTaskId());
        $skill = $this->skillService->findSkillById($taskSettingDTO->getSkillId());

        if (!isset($task, $skill)) {
            throw new NotFoundException();
        }

        $taskSetting = $this->taskSettingManager->create($task, $skill, $taskSettingDTO->getSkillValue());
        $this->checkExistErrorsValidation($taskSetting);
        $this->taskSettingManager->emFlush();

        return $taskSetting;
    }

    /**
     * @throws ValidationException
     */
    private function checkExistErrorsValidation(TaskSetting $taskSetting): void
    {
        $errors = $this->validator->validate($taskSetting);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    public function updateTaskSettingFromTaskSettingDTI(
        TaskSetting    $taskSetting,
        TaskSettingDTO $taskSettingDTO
    ): TaskSetting
    {
        $task = $this->taskService->findTaskById($taskSettingDTO->getTaskId());
        $skill = $this->skillService->findSkillById($taskSettingDTO->getSkillId());

        if (!isset($task, $skill)) {
            throw new NotFoundException();
        }

        $this->taskSettingManager->update($taskSetting, $task, $skill, $taskSettingDTO->getSkillValue());

        $this->checkExistErrorsValidation($taskSetting);
        $this->taskSettingManager->emFlush();

        return $taskSetting;
    }

    public function getTaskSettingWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var TaskSettingRepository $taskSettingRepository */
        $taskSettingRepository = $this->em->getRepository(TaskSetting::class);

        return $taskSettingRepository->getTaskSettingWithOffset($numberPage, $countInPage);
    }

    /**
     * @return TaskSetting []
     */
    public function getTaskSettingsByTask(Task $task): array
    {
        /** @var TaskSettingRepository $taskSettingRepository */
        $taskSettingRepository = $this->em->getRepository(TaskSetting::class);

        return $taskSettingRepository->findBy(['task' => $task]);
    }

    public function getTaskSettingByTaskAndSkill(Task $task, Skill $skill): ?TaskSetting
    {
        /** @var TaskSettingRepository $taskSettingRepository */
        $taskSettingRepository = $this->em->getRepository(TaskSetting::class);

        return $taskSettingRepository->findOneBy(['task' => $task, 'skill' => $skill]);
    }
}
