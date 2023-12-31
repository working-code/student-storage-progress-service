<?php

namespace App\Service;

use App\DTO\Builder\UserSkillDTOBuilder;
use App\DTO\Output\UserSkillDTO;
use App\Entity\Skill;
use App\Entity\User;
use App\Entity\UserSkill;
use App\Exception\EmptyValueException;
use App\Manager\UserSkillManager;
use App\Repository\UserSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserSkillService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserSkillManager       $userSkillManager,
        private readonly TagAwareCacheInterface $cache,
        private readonly UserSkillDTOBuilder    $userSkillDTOBuilder,
    )
    {
    }

    /**
     * @return UserSkillDTO[]
     * @throws InvalidArgumentException
     */
    public function getUserSkillByUser(User $user): array
    {
        $userSkillRepository = $this->em->getRepository(UserSkill::class);

        return $this->cache->get(
            "user_{$user->getId()}_skills",
            function (ItemInterface $item) use ($userSkillRepository, $user) {
                $userSkills = $userSkillRepository->findBy(['user' => $user]);
                $userSkills = array_map(
                    fn(UserSkill $userSkill) => $this->userSkillDTOBuilder->buildFromEntity($userSkill),
                    $userSkills
                );
                $item->set($userSkills);
                $item->tag(Cache::CACHE_TAG_USER_SKILLS . "{$user->getId()}");

                return $userSkills;
            }
        );
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

                $this->userSkillManager->emFlush();
            } else {
                $this->userSkillManager->delete($userSkill)
                    ->emFlush();
            }
        }

        foreach ($newSkillValues as $skillId => $skillValue) {
            $this->userSkillManager->create($user, $skills[$skillId], $skillValue);
            $this->userSkillManager->emFlush();
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
