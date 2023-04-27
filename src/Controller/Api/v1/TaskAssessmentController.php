<?php

namespace App\Controller\Api\v1;

use App\DTO\TaskAssessmentDTO;
use App\DTO\Builder\TaskAssessmentDTOBuilder;
use App\DTO\Input\TaskAssessmentWrapperDTO;
use App\Entity\TaskAssessment;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use App\Service\TaskAssessmentService;
use App\Symfony\MainParamConvertor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/task_assessment')]
class TaskAssessmentController extends BaseController
{
    public function __construct(
        private readonly TaskAssessmentService    $taskAssessmentService,
        private readonly TaskAssessmentDTOBuilder $taskAssessmentDTOBuilder,
    )
    {
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    #[Route('', methods: ['POST'])]
    #[ParamConverter(
        'taskAssessmentWrapperDTO',
        options: [MainParamConvertor::GROUPS => [TaskAssessmentDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function store(TaskAssessmentWrapperDTO $taskAssessmentWrapperDTO): Response
    {
        $taskAssessment = $this->taskAssessmentService->createTaskAssessmentFromAssessmentDTO(
            $taskAssessmentWrapperDTO->getTaskAssessmentDTO()
        );
        $assessmentDTO = $this->taskAssessmentDTOBuilder->buildFromEntity($taskAssessment);

        return $this->json(['taskAssessment' => $assessmentDTO], Response::HTTP_CREATED);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[ParamConverter('taskAssessment')]
    public function show(TaskAssessment $taskAssessment): Response
    {
        $taskAssessmentDTO = $this->taskAssessmentDTOBuilder->buildFromEntity($taskAssessment);

        return $this->json(['taskAssessment' => $taskAssessmentDTO], Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter('taskAssessment')]
    #[ParamConverter(
        'taskAssessmentWrapperDTO',
        options: [MainParamConvertor::GROUPS => [TaskAssessmentDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function update(
        TaskAssessment           $taskAssessment,
        TaskAssessmentWrapperDTO $taskAssessmentWrapperDTO
    ): Response
    {
        $taskAssessment = $this->taskAssessmentService->updateTaskAssessmentByTaskAssessmentDTO(
            $taskAssessment,
            $taskAssessmentWrapperDTO->getTaskAssessmentDTO()
        );
        $taskAssessmentDTO = $this->taskAssessmentDTOBuilder->buildFromEntity($taskAssessment);

        return $this->json(['taskAssessment' => $taskAssessmentDTO], Response::HTTP_OK);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    #[ParamConverter('taskAssessment')]
    public function delete(TaskAssessment $taskAssessment): Response
    {
        $this->taskAssessmentService->deleteTaskAssessment($taskAssessment);

        return $this->json([], Response::HTTP_OK);
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $numberPage = $request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);
        $countInPage = $request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);

        $taskAssessments = $this->taskAssessmentService->getTaskAssessmentWithOffset($numberPage, $countInPage);
        $data = ['taskAssessments' => array_map(
            fn(TaskAssessment $taskAssessment) => $this->taskAssessmentDTOBuilder->buildFromEntity($taskAssessment),
            $taskAssessments
        )];

        return $this->json($data, $taskAssessments ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
