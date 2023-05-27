<?php

namespace App\Event;

use App\Entity\TaskAssessment;

class CreatedTaskAssessmentEvent
{
    public function __construct(
        private readonly TaskAssessment $taskAssessment
    )
    {
    }

    public function getTaskAssessment(): TaskAssessment
    {
        return $this->taskAssessment;
    }
}
