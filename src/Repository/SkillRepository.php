<?php

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\ORM\EntityRepository;

class SkillRepository extends EntityRepository
{
    /**
     * @return Skill[]
     */
    public function getSkillsWithOffset(int $numberPage, int $countInPage): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->setFirstResult(--$numberPage * $countInPage)
            ->setMaxResults($countInPage)
            ->addOrderBy('s.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
