<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Entity\Task;
use App\Service\TaskAssessmentService;
use App\Service\TaskService;
use Doctrine\DBAL\Exception;

class TaskCollectionResolver implements QueryCollectionResolverInterface
{
    public function __construct(
        private readonly TaskService           $taskService,
        private readonly TaskAssessmentService $taskAssessmentService,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        $type = $context['args']['type'] ?? null;

        /** @var Task $task */
        foreach ($collection as $task) {
            if ($type && $task->isTaskTypeCourse()) {
                $tasks = $this->taskService->getTasksByCourse($task);
            } elseif ($type && $task->isTaskTypeLesson()) {
                $tasks = $task->getChildren()->toArray();
            } elseif ($type) {
                $tasks = [$task];
            }

            if ($type) {
                $task->setAssessmentAggregations($this->taskAssessmentService->getAggregationAssessmentByTasks($tasks));
            }
        }

        return $collection;
    }
}
