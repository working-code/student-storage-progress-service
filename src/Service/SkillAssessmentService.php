<?php

namespace App\Service;

use App\Entity\SkillAssessment;
use App\Entity\TaskAssessment;
use App\Entity\TaskSetting;
use App\Exception\ValidationException;
use App\Manager\SkillAssessmentManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SkillAssessmentService
{
    public function __construct(
        private readonly SkillAssessmentManager $skillAssessmentManager,
        private readonly ValidatorInterface     $validator,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function createSkillAssessment(
        TaskAssessment $taskAssessment,
        TaskSetting    $taskSetting
    ): SkillAssessment
    {
        $skillAssessment = $this->skillAssessmentManager->create(
            $taskSetting->getSkill(),
            $taskAssessment,
            $this->calculateSkillValue($taskAssessment, $taskSetting)
        );
        $this->checkExistErrorsValidation($skillAssessment);

        return $this->skillAssessmentManager->save($skillAssessment);
    }

    public function calculateSkillValue(TaskAssessment $taskAssessment, TaskSetting $taskSetting): int
    {
        return (int)floor($taskAssessment->getAssessment() * $taskSetting->getValuePercentage() / 100);
    }

    /**
     * @throws ValidationException
     */
    private function checkExistErrorsValidation(SkillAssessment $skillAssessment): void
    {
        $errors = $this->validator->validate($skillAssessment);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}
