<?php

namespace App\Tests\_support\Helper;

use App\Entity\Task;
use App\Entity\User;
use Codeception\Module;
use League\FactoryMuffin\Faker\Facade;

class Factories extends Module
{
    public function _beforeSuite($settings = []): void
    {
        $factory = $this->getModule('DataFactory');

        $factory->_define(
            User::class,
            [
                'surname' => Facade::text(20)(),
                'name' => Facade::text(20)(),
                'patronymic' => Facade::text(20)(),
                'email' => Facade::safeEmail()(),
                'password' => Facade::text(20)(),
            ]
        );
        $factory->_define(
            Task::class,
            [
                'title' => Facade::text(30)(),
                'content' => Facade::text(70)(),
            ]
        );
    }
}
