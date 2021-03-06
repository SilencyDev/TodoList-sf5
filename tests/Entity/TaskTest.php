<?php

namespace Test\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntity(): Task
    {
        return (new Task())
            ->setUser(new User())
            ->setContent('Lorem ipsum')
            ->setTitle('A test')
        ;
    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $error = self::$kernel->getContainer()->get('validator')->validate($task);
        $this->assertCount($number, $error);
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testUnvalidContentEntity()
    {
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }

    public function testUnvalidTitleEntity()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    public function testCreatedAtNotNullEntity()
    {
        $this->assertNotNull($this->getEntity()->getCreatedAt());
    }

    public function testToggleToTrueEntity()
    {
        $this->assertEquals($this->getEntity()->toggle(1)->IsDone(), 1);
    }

    public function testToggleToFalseEntity()
    {
        $this->assertEquals($this->getEntity()->toggle(0)->IsDone(), 0);
    }
}
