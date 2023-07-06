<?php

namespace App\EventSubscriber;

use App\DTO\Builder\RecalculateSkillsForUserDTOBuilder;
use App\Entity\Skill;
use App\Entity\SkillAssessment;
use App\Entity\TaskAssessment;
use App\Entity\User;
use App\Event\CreatedTaskAssessmentEvent;
use App\Event\DeletedTaskAssessmentEvent;
use App\Event\UpdatedTaskAssessmentEvent;
use App\Exception\ValidationException;
use App\Manager\SkillAssessmentManager;
use App\Manager\TaskAssessmentManager;
use App\Service\AsyncService;
use App\Service\SkillAssessmentService;
use App\Service\TaskAssessmentService;
use App\Service\TaskSettingService;
use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TaskAssessmentSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TaskSettingService                 $taskSettingService,
        private readonly SkillAssessmentManager             $skillAssessmentManager,
        private readonly SkillAssessmentService             $skillAssessmentService,
        private readonly TaskAssessmentManager              $taskAssessmentManager,
        private readonly TaskAssessmentService              $taskAssessmentService,
        private readonly AsyncService                       $asyncService,
        private readonly RecalculateSkillsForUserDTOBuilder $recalculateSkillsForUserDTOBuilder,
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
     * @throws JsonException
     */
    public function onDeletedTaskAssessment(DeletedTaskAssessmentEvent $deletedTaskAssessmentEvent): void
    {
        $this->asyncRecalculateSkillsForUser(
            $deletedTaskAssessmentEvent->getUser(),
            $deletedTaskAssessmentEvent->getSkills()
        );
    }

    /**
     * @param Skill[] $skills
     * @throws JsonException
     */
    private function asyncRecalculateSkillsForUser(User $user, array $skills): void
    {
        $this->asyncService->publishToExchange(
            AsyncService::RECALCULATE_SKILLS_FOR_USER,
            $this->recalculateSkillsForUserDTOBuilder->buildFromUserAndSkills($user, $skills)->toAMQPMessage()
        );
    }

    /**
     * @throws JsonException
     * @throws ValidationException
     */
    public function onCreatedTaskAssessment(CreatedTaskAssessmentEvent $createdTaskAssessmentEvent): void
    {
        $taskAssessment = $createdTaskAssessmentEvent->getTaskAssessment();
        $this->createAndAddSkillAssessmentsByTaskAssessment($taskAssessment);
        $this->taskAssessmentManager->emFlush();

        $skills = $this->taskAssessmentService->getSkillsByTaskAssessment($taskAssessment);
        $this->asyncRecalculateSkillsForUser($taskAssessment->getUser(), $skills);
    }

    /**
     * @throws ValidationException
     */
    private function createAndAddSkillAssessmentsByTaskAssessment(TaskAssessment $taskAssessment): void
    {
        $taskSettings = $this->taskSettingService->getTaskSettingsByTask($taskAssessment->getTask());

        foreach ($taskSettings as $taskSetting) {
            $skillAssessment = $this->skillAssessmentService->createSkillAssessment($taskAssessment, $taskSetting);
            $taskAssessment->addSkillAssessment($skillAssessment);
        }
    }

    /**
     * @throws JsonException
     * @throws ValidationException
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
                $this->asyncRecalculateSkillsForUser($oldTaskAssessment->getUser(), $oldSkills);
                $this->asyncRecalculateSkillsForUser($taskAssessment->getUser(), $newSkills);
            } else {
                $allSkills = array_merge($oldSkills, $newSkills);
                $this->asyncRecalculateSkillsForUser($taskAssessment->getUser(), $allSkills);
            }
        } else {
            $this->recalculateCurrentSkillAssessmentsByTaskAssessment($taskAssessment);

            $skills = $this->taskAssessmentService->getSkillsByTaskAssessment($taskAssessment);
            $this->asyncRecalculateSkillsForUser($taskAssessment->getUser(), $skills);
        }

        $this->taskAssessmentManager->emFlush();
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
            $this->skillAssessmentManager->emFlush();
        }
    }
}
