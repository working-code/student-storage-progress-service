<?php

namespace App\Service;

use App\Entity\Skill;
use App\Entity\User;
use App\Entity\UserSkill;
use App\Exception\EmptyValueException;
use App\Manager\UserSkillManager;
use App\Repository\UserSkillRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserSkillService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserSkillManager       $userSkillManager,
    )
    {
    }

    /**
     * @param User $user
     * @param Skill[] $skills
     * @return void
     * @throws EmptyValueException
     */
    public function recalculateSkillsForUser(User $user, array $skills): void
    {
        $skills = array_combine(array_map(static fn(Skill $skill) => $skill->getId(), $skills), $skills);

        /** @var UserSkillRepository $userSkillRepository */
        $userSkillRepository = $this->em->getRepository(UserSkill::class);
        $newSkillValues = $userSkillRepository->getTotalValueForSkillsByUser($skills, $user);
        $newSkillValues = array_combine(
            array_column($newSkillValues, 'skill_id'),
            array_column($newSkillValues, 'skill_value')
        );

        $currentUserSkills = $this->getUserSkillByUserAndSkillIds($user, $skills);

        foreach ($currentUserSkills as $userSkill) {
            $skillId = $userSkill->getSkill()->getId();

            if (isset($newSkillValues[$skillId])) {
                $userSkill->setValue($newSkillValues[$skillId]);
                unset($newSkillValues[$skillId]);

                $this->userSkillManager->update($userSkill);
            } else {
                $this->userSkillManager->delete($userSkill);
            }
        }

        foreach ($newSkillValues as $skillId => $skillValue) {
            $userSkill = $this->userSkillManager->create($user, $skills[$skillId], $skillValue);
            $this->userSkillManager->save($userSkill);
        }
    }

    /**
     * @param User $user
     * @param Skill[] $skills
     * @return UserSkill[]
     */
    private function getUserSkillByUserAndSkillIds(User $user, array $skills): array
    {
        $userSkillRepository = $this->em->getRepository(UserSkill::class);

        return $userSkillRepository->findBy(['user' => $user, 'skill' => $skills]);
    }
}
