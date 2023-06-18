<?php

namespace App\DTO\Output;

class RecalculateSkillsForUserDTO
{
    private int $userId;

    /** @var int[] */
    private array $skillIds;

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function setSkillIds(array $skillIds): self
    {
        $this->skillIds = $skillIds;

        return $this;
    }

    public function toAMQPMessage(): string
    {
        return json_encode([
            'userId' => $this->userId,
            'skillIds' => $this->skillIds,
        ], JSON_THROW_ON_ERROR);
    }
}
