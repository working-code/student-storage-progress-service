<?php

namespace App\Repository;

use App\Entity\UserAchievement;
use Doctrine\ORM\EntityRepository;

class UserAchievementRepository extends EntityRepository
{
    /**
     * @return UserAchievement[]
     */
    public function getUserAchievementsWithOffset(int $numberPage, int $countInPage): array
    {
        $qb = $this->createQueryBuilder('ua');
        $qb->setFirstResult(--$numberPage * $countInPage)
            ->setMaxResults($countInPage)
            ->addOrderBy('ua.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
