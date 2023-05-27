<?php

namespace App\DTO\Builder;

use App\DTO\TaskAssessmentDTO;
use App\Entity\TaskAssessment;

class TaskAssessmentDTOBuilder
{
    public function buildFromEntity(TaskAssessment $taskAssessment): TaskAssessmentDTO
    {
        return (new TaskAssessmentDTO())
            ->setId($taskAssessment->getId())
            ->setUserId($taskAssessment->getUser()->getId())
            ->setTaskId($taskAssessment->getTask()->getId())
            ->setAssessment($taskAssessment->getAssessment())
            ->setCreatedAt($taskAssessment->getCreatedAt())
            ->setUpdatedAt($taskAssessment->getUpdatedAt());
    }
}
