<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('usuario');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$7uhIVboM7x9L+GgMlPI/Pw$1jAeEnjLAgOopjsQhUOethVmcAH9OcyYMSQdPvlS2Qo');
        $manager->persist($user);

        $manager->flush();
    }
}
