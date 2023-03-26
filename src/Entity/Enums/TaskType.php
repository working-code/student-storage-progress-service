<?php

namespace App\Entity\Enums;

enum TaskType: int
{
    case Task = 1;
    case Lesson = 2;
    case Course = 3;
}
