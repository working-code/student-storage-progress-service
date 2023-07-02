<?php

namespace App\Service;

use App\DTO\SkillDTO;
use App\Entity\Skill;
use App\Exception\ValidationException;
use App\Manager\SkillManager;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SkillService
{
    public function __construct(
        private readonly SkillManager           $skillManager,
        private readonly ValidatorInterface     $validator,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function createSkillFromSkillDTO(SkillDTO $skillDTO): Skill
    {
        $skill = $this->skillManager->create($skillDTO->getName());

        $this->checkExistErrorsValidation($skill);
        $this->skillManager->emFlush();

        return $skill;
    }

    /**
     * @throws ValidationException
     */
    private function checkExistErrorsValidation(Skill $skill): void
    {
        $errors = $this->validator->validate($skill);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @throws ValidationException
     */
    public function updateSkillBySkillDTO(Skill $skill, SkillDTO $skillDTO): ?Skill
    {
        $this->skillManager->update($skill, $skillDTO->getName());

        $this->checkExistErrorsValidation($skill);
        $this->skillManager->emFlush();

        return $skill;
    }

    /**
     * @return Skill[]
     */
    public function getSkillsWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var SkillRepository $skillRepository */
        $skillRepository = $this->em->getRepository(Skill::class);

        return $skillRepository->getSkillsWithOffset($numberPage, $countInPage);
    }

    public function findSkillById(int $id): ?Skill
    {
        /** @var SkillRepository $skillRepository */
        $skillRepository = $this->em->getRepository(Skill::class);

        return $skillRepository->find($id);
    }

    /**
     * @param int[] $ids
     * @return Skill[]
     */
    public function findSkillsByIds(array $ids): array
    {
        /** @var SkillRepository $skillRepository */
        $skillRepository = $this->em->getRepository(Skill::class);

        return $skillRepository->findBy(['id' => $ids]);
    }
}
