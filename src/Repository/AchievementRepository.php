<?php

namespace App\Repository;

use App\Entity\Achievement;
use Doctrine\ORM\EntityRepository;

class AchievementRepository extends EntityRepository
{
    /**
     * @return Achievement[]
     */
    public function getAchievementWithOffset(int $numberPage, int $countInPage): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->setFirstResult(--$numberPage * $countInPage)
            ->setMaxResults($countInPage)
            ->addOrderBy('a.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
