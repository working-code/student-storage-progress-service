<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\TaskAssessment;
use App\Entity\User;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

class TaskAssessmentRepository extends EntityRepository
{
    /**
     * @return TaskAssessment[]
     */
    public function getTaskAssessmentWithOffset(int $numberPage, int $countInPage): array
    {
        $qb = $this->createQueryBuilder('ta');
        $qb->setFirstResult(--$numberPage * $countInPage)
            ->setMaxResults($countInPage)
            ->addOrderBy('ta.id', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getMinAssessmentByCourseAndStudents(
        array      $tasks,
        Collection $students,
        DateTime   $startDate = null,
        DateTime   $endDate = null
    ): array
    {
        $qb = $this->createQueryBuilder('ta');
        $qb->select(
            'u.id as user_id',
            $qb->expr()->min('ta.assessment') . ' as assessment'
        )
            ->join('ta.user', 'u')
            ->andWhere($qb->expr()->in('ta.task', array_map(static fn(Task $task) => $task->getId(), $tasks)))
            ->andWhere($qb->expr()->in('ta.user', $students->map(static fn(User $user) => $user->getId())))
            ->addGroupBy('u.id');

        if ($startDate) {
            $qb->andWhere('ta.createdAt >= :startDate')->setParameter('startDate', $startDate);
        }

        if ($endDate) {
            $qb->andWhere('ta.createdAt <= :endDate')->setParameter('endDate', $endDate);
        }

        return $qb->getQuery()->getResult();
    }

    public function getAggregationAssessmentByTasks(array $tasks): array
    {
        $qb = $this->createQueryBuilder('ta');
        $qb->select(
            'u.id as user_id',
            $qb->expr()->min('ta.assessment') . ' as min',
            $qb->expr()->max('ta.assessment') . ' as max',
            $qb->expr()->avg('ta.assessment') . ' as avg',
            $qb->expr()->count('ta.assessment') . ' as count',
        )
            ->join('ta.user', 'u')
            ->andWhere($qb->expr()->in('ta.task', array_map(static fn(Task $task) => $task->getId(), $tasks)))
            ->addGroupBy('u.id');

        return $qb->getQuery()->getResult();
    }
}
