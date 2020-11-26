<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername('Silency' . $i);

            // password = "test"
            $user->setPassword("$2y$13$3AM4cATsK383z8I2.GzJce6fH7cVZcnWm2rPuZDVhpNtJgjzKQUcK");
            $user->setEmail('test' . $i . '@test.fr');
            if ($i === 1) {
                $user->setRoles(['ROLE_USER']);
            } else {
                $user->setRoles((rand(0, 1) || $i == 0) ? ['ROLE_ADMIN'] : ['ROLE_USER']);
            }
            $this->taskCatalogue($user, $manager);
            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * @param User $user
     * @param ObjectManager $manager
     * @return void
     */
    private function taskCatalogue(User $user, ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        for ($k = 0; $k < 4; $k++) {
            $task = new Task();
            $task->setUser($user);
            $task->setContent($faker->sentence());
            $task->setTitle($faker->title());
            $task->toggle($faker->boolean());

            $manager->persist($task);
        }
    }
}
