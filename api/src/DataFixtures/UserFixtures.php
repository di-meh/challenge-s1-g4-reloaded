<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture {

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void {
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setVerified(true);
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setUsername('user');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(true);
        $manager->persist($user);

        $manager->flush();
    }
}
