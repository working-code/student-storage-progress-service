<?php

namespace App\EventSubscriber;

use App\Entity\SkillAssessment;
use App\Entity\TaskAssessment;
use App\Event\CreatedTaskAssessmentEvent;
use App\Event\DeletedTaskAssessmentEvent;
use App\Event\UpdatedTaskAssessmentEvent;
use App\Exception\EmptyValueException;
use App\Manager\SkillAssessmentManager;
use App\Manager\TaskAssessmentManager;
use App\Service\SkillAssessmentService;
use App\Service\TaskAssessmentService;
use App\Service\TaskSettingService;
use App\Service\UserSkillService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TaskAssessmentSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskSettingService     $taskSettingService,
        private readonly SkillAssessmentManager $skillAssessmentManager,
        private readonly SkillAssessmentService $skillAssessmentService,
        private readonly TaskAssessmentManager  $taskAssessmentManager,
        private readonly TaskAssessmentService  $taskAssessmentService,
        private readonly UserSkillService       $userSkillService,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreatedTaskAssessmentEvent::class => 'onCreatedTaskAssessment',
            UpdatedTaskAssessmentEvent::class => 'onUpdatedTaskAssessment',
            DeletedTaskAssessmentEvent::class => 'onDeletedTaskAssessment',
        ];
    }

    /**
     * @throws EmptyValueException
     */
    public function onDeletedTaskAssessment(DeletedTaskAssessmentEvent $deletedTaskAssessmentEvent): void
    {
        $this->userSkillService->recalculateSkillsForUser(
            $deletedTaskAssessmentEvent->getUser(),
            $deletedTaskAssessmentEvent->getSkills()
        );
    }

    /**
     * @throws EmptyValueException
     */
    public function onCreatedTaskAssessment(CreatedTaskAssessmentEvent $createdTaskAssessmentEvent): void
    {
        $taskAssessment = $createdTaskAssessmentEvent->getTaskAssessment();
        $this->createAndAddSkillAssessmentsByTaskAssessment($taskAssessment);
        $this->taskAssessmentManager->update($taskAssessment);

        $skills = $this->taskAssessmentService->getSkillsByTaskAssessment($taskAssessment);
        $this->userSkillService->recalculateSkillsForUser($taskAssessment->getUser(), $skills);
    }

    private function createAndAddSkillAssessmentsByTaskAssessment(TaskAssessment $taskAssessment): void
    {
        $taskSettings = $this->taskSettingService->getTaskSettingsByTask($taskAssessment->getTask());

        foreach ($taskSettings as $taskSetting) {
            $skillAssessment = $this->skillAssessmentService->createSkillAssessment($taskAssessment, $taskSetting);
            $taskAssessment->addSkillAssessment($skillAssessment);
        }
    }

    /**
     * @throws EmptyValueException
     */
    public function onUpdatedTaskAssessment(UpdatedTaskAssessmentEvent $updatedTaskAssessmentEvent): void
    {
        $taskAssessment = $updatedTaskAssessmentEvent->getTaskAssessment();
        $oldTaskAssessment = $updatedTaskAssessmentEvent->getOldTaskAssessment();
        $isChangedUser = $taskAssessment->getUser()->getId() != $oldTaskAssessment->getUser()->getId();
        $isChangedTask = $taskAssessment->getTask()->getId() != $oldTaskAssessment->getTask()->getId();

        if ($isChangedUser || $isChangedTask) {
            $oldSkills = $this->taskAssessmentService->getSkillsByTaskAssessment($oldTaskAssessment);
            $oldTaskAssessment->getSkillAssessments()->clear();

            $this->createAndAddSkillAssessmentsByTaskAssessment($taskAssessment);

            $newSkills = $this->taskAssessmentService->getSkillsByTaskAssessment($taskAssessment);

            if ($isChangedUser) {
                $this->userSkillService->recalculateSkillsForUser($oldTaskAssessment->getUser(), $oldSkills);
                $this->userSkillService->recalculateSkillsForUser($taskAssessment->getUser(), $newSkills);
            } else {
                $allSkills = array_merge($oldSkills, $newSkills);
                $this->userSkillService->recalculateSkillsForUser($taskAssessment->getUser(), $allSkills);
            }
        } else {
            $this->recalculateCurrentSkillAssessmentsByTaskAssessment($taskAssessment);

            $skills = $this->taskAssessmentService->getSkillsByTaskAssessment($taskAssessment);
            $this->userSkillService->recalculateSkillsForUser($taskAssessment->getUser(), $skills);
        }

        $this->taskAssessmentManager->update($taskAssessment);
    }

    private function recalculateCurrentSkillAssessmentsByTaskAssessment(TaskAssessment $taskAssessment): void
    {
        foreach ($taskAssessment->getSkillAssessments() as $skillAssessment) {
            /** @var SkillAssessment $skillAssessment */
            $taskSetting = $this->taskSettingService->getTaskSettingByTaskAndSkill(
                $taskAssessment->getTask(),
                $skillAssessment->getSkill()
            );

            $skillAssessment->setSkillValue(
                $this->skillAssessmentService->calculateSkillValue($taskAssessment, $taskSetting)
            );
            $this->skillAssessmentManager->update($skillAssessment);
        }
    }
}
