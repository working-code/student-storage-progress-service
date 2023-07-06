<?php

namespace App\Controller\Api\v1;

use App\DTO\Builder\TaskSettingDTOBuilder;
use App\DTO\Input\TaskSettingWrapperDTO;
use App\DTO\TaskSettingDTO;
use App\Entity\TaskSetting;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use App\Manager\TaskSettingManager;
use App\Service\TaskSettingService;
use App\Symfony\MainParamConvertor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/task_setting')]
class TaskSettingController extends BaseController
{
    public function __construct(
        private readonly TaskSettingService    $taskSettingService,
        private readonly TaskSettingDTOBuilder $taskSettingDTOBuilder,
    )
    {
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    #[Route('', methods: ['POST'])]
    #[ParamConverter(
        'taskSettingWrapperDTO',
        options: [MainParamConvertor::GROUPS => [TaskSettingDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function store(TaskSettingWrapperDTO $taskSettingWrapperDTO): Response
    {
        $taskSetting = $this->taskSettingService->createTaskSettingFromTaskSettingDTO(
            $taskSettingWrapperDTO->getTaskSettingDTO()
        );
        $taskSettingDTO = $this->taskSettingDTOBuilder->builderFromEntity($taskSetting);

        return $this->json(['taskSetting' => $taskSettingDTO], Response::HTTP_CREATED);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(TaskSetting $taskSetting): Response
    {
        $taskSettingDTO = $this->taskSettingDTOBuilder->builderFromEntity($taskSetting);

        return $this->json(['taskSetting' => $taskSettingDTO], Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter('taskSetting')]
    #[ParamConverter(
        'taskSettingWrapperDTO',
        options: [MainParamConvertor::GROUPS => [TaskSettingDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function update(TaskSetting $taskSetting, TaskSettingWrapperDTO $taskSettingWrapperDTO): Response
    {
        $taskSetting = $this->taskSettingService->updateTaskSettingFromTaskSettingDTI(
            $taskSetting,
            $taskSettingWrapperDTO->getTaskSettingDTO()
        );
        $taskSettingDTO = $this->taskSettingDTOBuilder->builderFromEntity($taskSetting);

        return $this->json(['taskSetting' => $taskSettingDTO], Response::HTTP_OK);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    #[ParamConverter('taskSetting')]
    public function delete(TaskSetting $taskSetting, TaskSettingManager $taskSettingManager): Response
    {
        $taskSettingManager->delete($taskSetting)
            ->emFlush();

        return $this->json([], Response::HTTP_OK);
    }

    #[Route(path: '', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $numberPage = $request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);
        $countInPage = $request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);

        $taskSettings = $this->taskSettingService->getTaskSettingWithOffset($numberPage, $countInPage);
        $data = ['taskSettings' => array_map(
            fn(TaskSetting $taskSetting) => $this->taskSettingDTOBuilder->builderFromEntity($taskSetting),
            $taskSettings
        )];

        return $this->json($data, $taskSettings ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
