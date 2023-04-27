<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @return User[]
     */
    public function getUsersWithOffset(int $numberPage, int $countInPage): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb->addOrderBy('u.id', 'ASC')
            ->setFirstResult(--$numberPage * $countInPage)
            ->setMaxResults($countInPage);

        return $qb->getQuery()->getResult();
    }
}
