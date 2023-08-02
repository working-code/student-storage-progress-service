<?php

namespace App\Repository;

use App\Entity\UserCourse;
use Doctrine\ORM\EntityRepository;

class UserCourseRepository extends EntityRepository
{
    /**
     * @return UserCourse[]
     */
    public function getUserCourseWithOffset(int $numberPage, int $countInPage): array
    {
        $qb = $this->createQueryBuilder('uc');
        $qb->setFirstResult(--$numberPage * $countInPage)
            ->setMaxResults($countInPage)
            ->addOrderBy('uc.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
