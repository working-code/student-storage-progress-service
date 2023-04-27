<?php

namespace App\Controller\Api\v1;

use App\DTO\Builder\SkillDTOBuilder;
use App\DTO\Input\SkillWrapperDTO;
use App\DTO\SkillDTO;
use App\Entity\Skill;
use App\Exception\ValidationException;
use App\Manager\SkillManager;
use App\Service\SkillService;
use App\Symfony\MainParamConvertor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/skill')]
class SkillController extends BaseController
{
    public function __construct(
        private readonly SkillService    $skillService,
        private readonly SkillDTOBuilder $skillDTOBuilder,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    #[Route(path: '', methods: ['POST'])]
    #[ParamConverter(
        'skillWrapperDTO',
        options: [MainParamConvertor::GROUPS => [SkillDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function store(SkillWrapperDTO $skillWrapperDTO): Response
    {
        $skill = $this->skillService->createSkillFromSkillDTO($skillWrapperDTO->getSkillDTO());
        $skillDTO = $this->skillDTOBuilder->buildFromEntity($skill);

        return $this->json(['skill' => $skillDTO], Response::HTTP_CREATED);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[ParamConverter('skill')]
    public function show(Skill $skill): Response
    {
        $skillDTO = $this->skillDTOBuilder->buildFromEntity($skill);

        return $this->json(['skill' => $skillDTO], Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter('skill')]
    #[ParamConverter(
        'skillWrapperDTO',
        options: [MainParamConvertor::GROUPS => [SkillDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function update(Skill $skill, SkillWrapperDTO $skillWrapperDTO): Response
    {
        $skill = $this->skillService->updateSkillBySkillDTO($skill, $skillWrapperDTO->getSkillDTO());
        $skillDTO = $this->skillDTOBuilder->buildFromEntity($skill);

        return $this->json(['skill' => $skillDTO], Response::HTTP_OK);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    #[ParamConverter('skill')]
    public function delete(Skill $skill, SkillManager $skillManager): Response
    {
        $skillManager->delete($skill);

        return $this->json([], Response::HTTP_OK);
    }

    #[Route(path: '', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $numberPage = $request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);
        $countInPage = $request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);

        $skills = $this->skillService->getSkillsWithOffset($numberPage, $countInPage);
        $data = ['skills' => array_map(fn(Skill $skill) => $this->skillDTOBuilder->buildFromEntity($skill), $skills)];

        return $this->json($data, $skills ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
