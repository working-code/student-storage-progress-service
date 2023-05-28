<?php

namespace App\Controller\Api\v1;

use App\DTO\Builder\UserDTOBuilder;
use App\DTO\Input\UserWrapperDTO;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Exception\ValidationException;
use App\Manager\UserManager;
use App\Service\UserService;
use App\Symfony\MainParamConvertor;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/user')]
class UserController extends BaseController
{
    public function __construct(
        private readonly UserService    $userService,
        private readonly UserDTOBuilder $userDTOBuilder,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    #[Route(path: '', methods: ['POST'])]
    #[ParamConverter(
        'userWrapperDTO',
        options: [MainParamConvertor::GROUPS => [UserDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function store(UserWrapperDTO $userWrapperDTO): Response
    {
        $user = $this->userService->createUserFromUserDTO($userWrapperDTO->getUserDTO());
        $userDTO = $this->userDTOBuilder->buildFromEntity($user);

        return $this->json(['user' => $userDTO], Response::HTTP_CREATED);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[ParamConverter('user')]
    public function show(User $user): Response
    {
        $userDTO = $this->userDTOBuilder->buildFromEntity($user);

        return $this->json(['user' => $userDTO], Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    #[Route(path: ['/{id}'], requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter('user')]
    #[ParamConverter(
        'userWrapperDTO',
        options: [MainParamConvertor::GROUPS => [UserDTO::DEFAULT]],
        converter: MainParamConvertor::MAIN_CONVERTOR
    )]
    public function update(User $user, UserWrapperDTO $userWrapperDTO): Response
    {
        $user = $this->userService->updateUserByUserDTO($user, $userWrapperDTO->getUserDTO());
        $userDTO = $this->userDTOBuilder->buildFromEntity($user);

        return $this->json(['user' => $userDTO], Response::HTTP_OK);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    #[ParamConverter('user')]
    public function delete(User $user, UserManager $userManager): Response
    {
        $userManager->delete($user);

        return $this->json([], Response::HTTP_OK);
    }

    #[Route(name: '', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $countInPage = (int)$request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);
        $numberPage = (int)$request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);

        $users = $this->userService->getUsersWithOffset($numberPage, $countInPage);
        $data = ['users' => array_map(fn(User $user) => $this->userDTOBuilder->buildFromEntity($user), $users)];

        return $this->json($data, $data ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
    }
}
