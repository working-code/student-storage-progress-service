<?php

namespace App\Repository;

use App\Entity\Enums\TaskType;
use App\Entity\Task;
use Doctrine\DBAL\Exception;
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

    /**
     * @return Task[]
     *
     * @throws Exception
     */
    public function getTasksByCourse(Task $course): array
    {
        $qb = $this->_em->getConnection()->createQueryBuilder();
        $qb->select('id')
            ->from('task', 'l')
            ->join(
                'l',
                'parent_children',
                'pc',
                $qb->expr()->and(
                    'l.id = pc.child_id',
                    $qb->expr()->eq('pc.parent_id', $course->getId())
                )
            );
        $lessonIds = $qb->executeQuery()->fetchFirstColumn();

        $qb = $this->_em->getConnection()->createQueryBuilder();
        $qb->select('id')
            ->from('task', 'l')
            ->join(
                'l',
                'parent_children',
                'pc',
                $qb->expr()->and(
                    'l.id = pc.child_id',
                    $qb->expr()->in('pc.parent_id', $lessonIds)
                )
            );
        $taskIds = $qb->executeQuery()->fetchFirstColumn();

        return $this->findBy(['id' => $taskIds]);
    }
}
