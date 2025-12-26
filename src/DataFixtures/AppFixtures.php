<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1 ->setEmail("test@test.com");
        $user1 ->setPassword($this->userPasswordHasher->hashPassword($user1, 'test'));
        $manager->persist($user1);

        $user2 = new User();
        $user2 ->setEmail("hande@test.com");
        $user2 ->setPassword($this->userPasswordHasher->hashPassword($user2, 'test'));
        $manager->persist($user2);


        $microPost1 = new MicroPost();
        $microPost1 ->setTitle('Welcome to Turkiye');
        $microPost1 ->setText('Welcome to Turkiye');
        $microPost1 ->setCreated(new DateTime());
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2 ->setTitle('Welcome to Asia');
        $microPost2 ->setText('Welcome to Asia');
        $microPost2 ->setCreated(new DateTime());
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3 ->setTitle('Welcome to Europe');
        $microPost3 ->setText('Welcome to Europe');
        $microPost3 ->setCreated(new DateTime());
        $manager->persist($microPost3);

        $manager->flush();
    }
}
