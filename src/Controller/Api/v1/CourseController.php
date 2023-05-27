<?php

namespace App\Controller\Api\v1;

use App\DTO\Builder\CourseDTOBuilder;
use App\DTO\CourseDTO;
use App\DTO\Input\CourseDTOWrapper;
use App\Entity\Task;
use App\Exception\ValidationException;
use App\Manager\CourseManager;
use App\Service\CourseService;
use App\Symfony\MainParamConvertor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/course')]
class CourseController extends BaseController
{
    public function __construct(
        private readonly CourseService    $courseService,
        private readonly CourseDTOBuilder $courseDTOBuilder,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    #[Route('', methods: ['POST'])]
    #[ParamConverter(
        'courseDTOWrapper',
        options: [MainParamConvertor::GROUPS => [CourseDTO::DEFAULT, CourseDTO::LESSONS]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function store(CourseDTOWrapper $courseDTOWrapper): Response
    {
        $course = $this->courseService->createCourseFromCourseWrapperDTO($courseDTOWrapper);

        return $this->json(['course' => $this->courseDTOBuilder->buildFromEntity($course)], Response::HTTP_CREATED);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): Response
    {
        $course = $this->courseService->findCourseById($id);

        return $course
            ? $this->json(['course' => $this->courseDTOBuilder->buildFromEntity($course)], Response::HTTP_OK)
            : $this->json([], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter(
        'courseDTOWrapper',
        options: [MainParamConvertor::GROUPS => [CourseDTO::DEFAULT, CourseDTO::LESSONS]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function update(int $id, CourseDTOWrapper $courseDTOWrapper): Response
    {
        $course = $this->courseService->findCourseById($id);

        if ($course) {
            $course = $this->courseService->updateCourseFromCourseWrapperDTO($course, $courseDTOWrapper);
        }

        return $course
            ? $this->json(['course' => $this->courseDTOBuilder->buildFromEntity($course)], Response::HTTP_OK)
            : $this->json([], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id, CourseManager $courseManager): Response
    {
        if ($course = $this->courseService->findCourseById($id)) {
            $courseManager->delete($course);
        }

        return $this->json([], $course ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $numberPage = $request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);
        $countInPage = $request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);

        $courses = $this->courseService->getCourseWithOffset($numberPage, $countInPage);
        $data = [
            'courses' => array_map(fn(Task $course) => $this->courseDTOBuilder->buildFromEntity($course), $courses)
        ];

        return $this->json($data, $courses ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
