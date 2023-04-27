<?php

namespace App\Controller\Api\v1;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/users')]
class UserController extends BaseController
{
    #[Route(name: '', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $countInPage = (int)$request->query->get('countInPage', static::DEFAULT_COUNT_IN_PAGE);
        $numberPage = (int)$request->query->get('numberPage', static::DEFAULT_NUMBER_PAGE);

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->getUsersWithOffset($numberPage, $countInPage);
        $users = array_map(static function (User $user) {
            return [
                'id' => $user->getId(),
                'surname' => $user->getSurname(),
                'name' => $user->getName(),
                'patronymic' => $user->getPatronymic(),
                'email' => $user->getEmail(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $users);
        $code = $users ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;

        return new JsonResponse(['users' => $users], $code);
    }
}
