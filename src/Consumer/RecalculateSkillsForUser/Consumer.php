<?php

namespace App\Consumer\RecalculateSkillsForUser;

use App\Consumer\RecalculateSkillsForUser\Input\Message;
use App\Entity\User;
use App\Service\Cache;
use App\Service\SkillService;
use App\Service\UserService;
use App\Service\UserSkillService;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Throwable;

class Consumer implements ConsumerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ValidatorInterface     $validator,
        private readonly UserSkillService       $userSkillService,
        private readonly UserService            $userService,
        private readonly SkillService           $skillService,
        private readonly TagAwareCacheInterface $cache,
    )
    {
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = Message::createFromQueue($msg->getBody());
            $errors = $this->validator->validate($message);

            if ($errors->count() > 0) {
                return $this->reject((string)$errors);
            }

            $user = $this->userService->findUserById($message->getUserId());

            if (!($user instanceof User)) {
                return $this->reject(sprintf('User ID %s was not found', $message->getUserId()));
            }

            $skills = $this->skillService->findSkillsByIds($message->getSkillIds());

            if (!$skills) {
                return $this->reject('Not found skills');
            }

            $this->userSkillService->recalculateSkillsForUser($user, $skills);
            $this->cache->invalidateTags([Cache::CACHE_TAG_USER_SKILLS . $user->getId()]);
        } catch (Throwable $exception) {
            return $this->reject($exception->getMessage());
        } finally {
            $this->em->clear();
            $this->em->getConnection()->close();
        }

        return static::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }
}
