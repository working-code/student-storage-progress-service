<?php

namespace App\Repository;

use App\Entity\Skill;
use App\Entity\SkillAssessment;
use App\Entity\User;
use App\Exception\EmptyValueException;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class UserSkillRepository extends EntityRepository
{
    /**
     * @param Skill[] $skills
     * @param User $user
     * @return array
     * @throws EmptyValueException
     */
    public function getTotalValueForSkillsByUser(array $skills, User $user): array
    {
        if (!$skills) {
            throw new EmptyValueException();
        }

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select([
            '(sa.skill) as skill_id',
            'SUM(sa.skillValue) as skill_value',
        ])
            ->from(SkillAssessment::class, 'sa')
            ->join('sa.taskAssessment', 'ta')
            ->andWhere('sa.skill IN (:skills)')
            ->setParameter(':skills', $skills)
            ->andWhere('ta.user = :user')
            ->setParameter(':user', $user)
            ->groupBy('sa.skill');

        return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
