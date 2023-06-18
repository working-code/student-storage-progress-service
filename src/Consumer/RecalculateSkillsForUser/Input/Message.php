<?php

namespace App\Consumer\RecalculateSkillsForUser\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    #[Assert\Type('numeric')]
    private int $userId;

    /** @var int[] */
    #[Assert\NotBlank]
    private array $skillIds;

    public static function createFromQueue(string $messageBody): self
    {
        $messageData = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $message = new static();
        $message->userId = $messageData['userId'];
        $message->skillIds = $messageData['skillIds'];

        return $message;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getSkillIds(): array
    {
        return $this->skillIds;
    }
}
