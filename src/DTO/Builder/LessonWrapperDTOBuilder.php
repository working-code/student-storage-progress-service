<?php

namespace App\DTO\Builder;

use App\DTO\Input\LessonWrapperDTO;

class LessonWrapperDTOBuilder
{
    public function __construct(private readonly LessonDTOBuilder $lessonDTOBuilder)
    {
    }

    public function buildFromArray(array $data): LessonWrapperDTO
    {
        $lessonWrapperDTO = new LessonWrapperDTO();

        if (isset($data['lesson'])) {
            $lessonWrapperDTO->setLessonDTO($this->lessonDTOBuilder->buildFromArray($data['lesson']));
        }
        if (isset($data['taskIds'])) {
            $lessonWrapperDTO->setTaskIds($data['taskIds']);
        }

        return $lessonWrapperDTO;
    }
}
