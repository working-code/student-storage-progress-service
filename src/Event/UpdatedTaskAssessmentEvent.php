<?php

namespace App\Event;

use App\Entity\TaskAssessment;

class UpdatedTaskAssessmentEvent
{
    public function __construct(
        private readonly TaskAssessment $taskAssessment,
        private readonly TaskAssessment $oldTaskAssessment,
    )
    {
    }

    public function getTaskAssessment(): TaskAssessment
    {
        return $this->taskAssessment;
    }

    public function getOldTaskAssessment(): TaskAssessment
    {
        return $this->oldTaskAssessment;
    }
}
