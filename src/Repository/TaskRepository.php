<?php

namespace App\Repository;

use App\Entity\Enums\TaskType;
use App\Entity\Task;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    /**
     * @return Task[]
     */
    public function getTaskWithOffset(int $numberPage, int $countInPage, TaskType $taskType): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->andWhere($qb->expr()->eq('t.type', $taskType->value))
            ->setFirstResult(--$numberPage * $countInPage)
            ->setMaxResults($countInPage)
            ->addOrderBy('t.id', 'ASC');

        return $qb->getQuery()
            ->enableResultCache(300, "task{$taskType->value}_{$numberPage}_{$countInPage}")
            ->getResult();
    }
}
