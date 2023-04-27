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
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u')
            ->addOrderBy('u.id', 'ASC')
            ->setFirstResult(--$numberPage * $countInPage)
            ->setMaxResults($countInPage);

        return $qb->getQuery()->getResult();
    }
}
