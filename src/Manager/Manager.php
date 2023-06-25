<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class Manager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create()
    {

    }

    public function save()
    {

    }

    public function update()
    {

    }

    public function delete(): void
    {

    }
}
