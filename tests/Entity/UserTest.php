<?php

namespace Test\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(): User
    {
        return (new User())
        ->setUsername('LoremIpsum')
        ->setRoles(['ROLE_ADMIN'])
        ->setEmail('email@email.com')
        ->setPassword('test');
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $error = self::$kernel->getContainer()->get('validator')->validate($user);
        $this->assertCount($number, $error);
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testUnvalidUsernameEntity()
    {
        $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
    }

    public function testUnvalidEmailEntity()
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testUnvalidPasswordEntity()
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
    }

    public function testAddRemoveTask()
    {
        $user = $this->getEntity();
        $user->addTask($task = new Task);
        $this->assertCount(1, $user->GetTasks());

        $user->removeTask($task);
        $this->assertCount(0, $user->GetTasks());
    }
}