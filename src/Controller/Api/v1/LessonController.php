<?php

namespace App\Controller\Api\v1;

use App\DTO\Builder\LessonDTOBuilder;
use App\DTO\Input\LessonWrapperDTO;
use App\DTO\LessonDTO;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Manager\LessonManager;
use App\Service\LessonService;
use App\Symfony\MainParamConvertor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/lesson')]
class LessonController extends BaseController
{
    public function __construct(
        private readonly LessonService    $lessonService,
        private readonly LessonDTOBuilder $lessonDTOBuilder,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    #[Route('', methods: ['POST'])]
    #[ParamConverter(
        'lessonWrapperDTO',
        options: [MainParamConvertor::GROUPS => [LessonDTO::DEFAULT, LessonDTO::TASKS]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function store(LessonWrapperDTO $lessonWrapperDTO): Response
    {
        $lesson = $this->lessonService->createLessonFromLessonWrapperDTO($lessonWrapperDTO);

        return $this->json(['lesson' => $this->lessonDTOBuilder->buildFromEntity($lesson)], Response::HTTP_CREATED);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): Response
    {
        $lesson = $this->lessonService->findLessonById($id);

        return $lesson
            ? $this->json(['lesson' => $this->lessonDTOBuilder->buildFromEntity($lesson)], Response::HTTP_OK)
            : $this->json(['description' => 'object not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws ValidationException
     */
    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter(
        'lessonWrapperDTO',
        options: [MainParamConvertor::GROUPS => [LessonDTO::DEFAULT, LessonDTO::TASKS]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function update(int $id, LessonWrapperDTO $lessonWrapperDTO): Response
    {
        $lesson = $this->lessonService->findLessonById($id);

        if ($lesson) {
            $lesson = $this->lessonService->updateLessonFromLessonWrapperDTO($lesson, $lessonWrapperDTO);
        }

        return $lesson
            ? $this->json(['lesson' => $this->lessonDTOBuilder->buildFromEntity($lesson)], Response::HTTP_OK)
            : $this->json([], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id, LessonManager $lessonManager): Response
    {
        if ($lesson = $this->lessonService->findLessonById($id)) {
            $lessonManager->delete($lesson)
                ->emFlush();
        }

        return $this->json([], $lesson ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $numberPage = $request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);
        $countInPage = $request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);

        $lessons = $this->lessonService->getLessonWithOffset($numberPage, $countInPage);
        $data = [
            'lessons' => array_map(fn(Task $lesson) => $this->lessonDTOBuilder->buildFromEntity($lesson), $lessons)
        ];

        return $this->json($data, $lessons ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
