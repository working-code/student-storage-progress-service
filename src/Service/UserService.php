<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Exception\ValidationException;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserManager            $userManager,
        private readonly ValidatorInterface     $validator,
        private readonly AuthService            $authService,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function createUserFromUserDTO(UserDTO $userDTO): User
    {
        $user = $this->userManager->create(
            $userDTO->getSurname(),
            $userDTO->getName(),
            $userDTO->getPatronymic(),
            $userDTO->getEmail(),
            $userDTO->getRoles(),
            ''
        );
        $user->setPassword($this->authService->getHashPassword($user, $userDTO->getPassword()));

        $this->checkExistErrorsValidation($user);

        return $this->userManager->save($user);
    }

    /**
     * @throws ValidationException
     */
    private function checkExistErrorsValidation(User $user): void
    {
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @throws ValidationException
     */
    public function updateUserByUserDTO(User $user, UserDTO $userDTO): User
    {
        $user->setSurname($userDTO->getSurname())
            ->setName($userDTO->getName())
            ->setPatronymic($userDTO->getPatronymic())
            ->setEmail($userDTO->getEmail())
            ->setRoles($userDTO->getRoles())
            ->setPassword($this->authService->getHashPassword($user, $userDTO->getPassword()));

        $this->checkExistErrorsValidation($user);

        return $this->userManager->update($user);
    }

    public function findUserById(int $userId): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository(User::class);

        return $userRepository->find($userId);
    }

    public function findUserByEmail(string $email): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository(User::class);

        return $userRepository->findOneBy(['email' => $email]);
    }

    /**
     * @return User[]
     */
    public function getUsersWithOffset(int $numberPage, int $countInPage): array
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository(User::class);

        return $userRepository->getUsersWithOffset($numberPage, $countInPage);
    }
}
