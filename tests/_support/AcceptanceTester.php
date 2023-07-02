<?php

namespace App\Tests;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    public function authForUser(string $login): void
    {
        match ($login) {
            'admin' => $this->amAdmin(),
            'student' => $this->amStudent(),
            'teacher' => $this->amTeacher(),
            default => null,
        };
    }

    public function amAdmin(): void
    {
        $this->amHttpAuthenticated('admin', 'admin');
    }

    public function amStudent(): void
    {
        $this->amHttpAuthenticated('student', 'student');
    }

    public function amTeacher(): void
    {
        $this->amHttpAuthenticated('teacher', 'teacher');
    }
}
