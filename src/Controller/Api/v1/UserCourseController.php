<?php

namespace App\Controller\Api\v1;

use App\DTO\Builder\UserCourseDTOBuilder;
use App\DTO\Input\UserCourseWrapperDTO;
use App\DTO\UserCourseDTO;
use App\Entity\UserCourse;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use App\Manager\UserCourseManager;
use App\Service\UserCourseService;
use App\Symfony\MainParamConvertor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/user_course')]
class UserCourseController extends BaseController
{
    public function __construct(
        private readonly UserCourseService    $userCourseService,
        private readonly UserCourseDTOBuilder $userCourseDTOBuilder,
    )
    {
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    #[Route(path: '', methods: ['POST'])]
    #[ParamConverter(
        'userCourseWrapperDTO',
        options: [MainParamConvertor::GROUPS => [UserCourseDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function store(UserCourseWrapperDTO $userCourseWrapperDTO): Response
    {
        $userCourse = $this->userCourseService
            ->createUserCourseFromUserCourseDTO($userCourseWrapperDTO->getUserCourseDTO());
        $userCourseDTO = $this->userCourseDTOBuilder->buildFromEntity($userCourse);

        return $this->json(['userCourse' => $userCourseDTO], Response::HTTP_CREATED);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(UserCourse $userCourse): Response
    {
        $userCourseDTO = $this->userCourseDTOBuilder->buildFromEntity($userCourse);

        return $this->json(['userCourse' => $userCourseDTO], Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     * @throws NotFoundException
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter('userCourse')]
    #[ParamConverter(
        'userCourseWrapperDTO',
        options: [MainParamConvertor::GROUPS => [UserCourseDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function update(UserCourse $userCourse, UserCourseWrapperDTO $userCourseWrapperDTO): Response
    {
        $userCourse = $this->userCourseService
            ->updateUserCourseFromUserCourseDTO($userCourse, $userCourseWrapperDTO->getUserCourseDTO());
        $userCourseDTO = $this->userCourseDTOBuilder->buildFromEntity($userCourse);

        return $this->json(['userCourse' => $userCourseDTO], Response::HTTP_OK);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(UserCourse $userCourse, UserCourseManager $userCourseManager): Response
    {
        $userCourseManager->delete($userCourse)
            ->emFlush();

        return $this->json([], Response::HTTP_OK);
    }

    #[Route(path: '', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $numberPage = $request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);
        $countInPage = $request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);

        $userCourses = $this->userCourseService->getUserCourseWithOffset($numberPage, $countInPage);
        $data = ['userCourses' => array_map(
            fn(UserCourse $userCourse) => $this->userCourseDTOBuilder->buildFromEntity($userCourse),
            $userCourses
        )];

        return $this->json($data, $userCourses ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
