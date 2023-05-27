<?php

namespace App\DTO\Input;

use App\DTO\SkillDTO;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;

class SkillWrapperDTO
{
    #[Assert\NotNull]
    #[Assert\Valid]
    #[SerializedName('skill')]
    #[Groups(SkillDTO::DEFAULT)]
    private ?SkillDTO $skillDTO;

    public function getSkillDTO(): ?SkillDTO
    {
        return $this->skillDTO;
    }

    public function setSkillDTO(?SkillDTO $skillDTO): self
    {
        $this->skillDTO = $skillDTO;

        return $this;
    }
}
