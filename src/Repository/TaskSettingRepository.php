<?php

namespace App\Repository;

use App\Entity\TaskSetting;
use Doctrine\ORM\EntityRepository;

class TaskSettingRepository extends EntityRepository
{
    /**
     * @return TaskSetting[]
     */
    public function getTaskSettingWithOffset(int $numberPage, int $countInPage): array
    {
        $qb = $this->createQueryBuilder('ts');
        $qb->setFirstResult(--$numberPage * $countInPage)
            ->setMaxResults($countInPage)
            ->addOrderBy('ts.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
