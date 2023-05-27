<?php

namespace App\Repository;

use App\Entity\TaskAssessment;
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
}
