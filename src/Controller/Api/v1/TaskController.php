<?php

namespace App\Controller\Api\v1;

use App\DTO\Builder\TaskDTOBuilder;
use App\DTO\Input\TaskWrapperDTO;
use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Manager\TaskManager;
use App\Service\TaskService;
use App\Symfony\MainParamConvertor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/task')]
class TaskController extends BaseController
{
    public function __construct(
        private readonly TaskService    $taskService,
        private readonly TaskDTOBuilder $taskDTOBuilder,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    #[Route('', methods: ['POST'])]
    #[ParamConverter(
        'taskWrapperDTO',
        options: [MainParamConvertor::GROUPS => [TaskDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function store(TaskWrapperDTO $taskWrapperDTO): Response
    {
        $task = $this->taskService->createTaskFromTaskDTO($taskWrapperDTO->getTaskDTO());

        return $this->json(['task' => $this->taskDTOBuilder->buildFromEntity($task)], Response::HTTP_CREATED);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): Response
    {
        $task = $this->taskService->findTaskById($id);

        return $task
            ? $this->json(['task' => $this->taskDTOBuilder->buildFromEntity($task)], Response::HTTP_OK)
            : $this->json(['description' => 'object not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws ValidationException
     */
    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter(
        'taskWrapperDTO',
        options: [MainParamConvertor::GROUPS => [TaskDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function update(int $id, TaskWrapperDTO $taskWrapperDTO): Response
    {
        $task = $this->taskService->findTaskById($id);

        if ($task) {
            $task = $this->taskService->updateTaskFromTaskDTO($task, $taskWrapperDTO->getTaskDTO());
        }

        return $task
            ? $this->json(['task' => $this->taskDTOBuilder->buildFromEntity($task)], Response::HTTP_OK)
            : $this->json([], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id, TaskManager $taskManager): Response
    {
        if ($task = $this->taskService->findTaskById($id)) {
            $taskManager->delete($task)
                ->emFlush();
        }

        return $this->json([], $task ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $numberPage = $request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);
        $countInPage = $request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);

        $tasks = $this->taskService->getTaskWithOffset($numberPage, $countInPage);
        $data = ['tasks' => array_map(fn(Task $task) => $this->taskDTOBuilder->buildFromEntity($task), $tasks)];

        return $this->json($data, $tasks ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
