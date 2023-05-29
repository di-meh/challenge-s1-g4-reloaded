<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture {

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
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

        $vendeur = new User();
        $vendeur->setEmail('vendeur@example.com');
        $vendeur->setUsername('vendeur');
        $vendeur->setPassword($this->passwordHasher->hashPassword($vendeur, 'vendeur'));
        $vendeur->setRoles(['ROLE_VENDEUR']);
        $vendeur->setVerified(true);
        $vendeur->setTel($faker->phoneNumber);
        $vendeur->setAdresse($faker->streetAddress);
        $manager->persist($vendeur);

        $annonceur = new User();
        $annonceur->setEmail('annonceur@example.com');
        $annonceur->setUsername('annonceur');
        $annonceur->setPassword($this->passwordHasher->hashPassword($annonceur, 'annonceur'));
        $annonceur->setRoles(['ROLE_ANNONCEUR']);
        $annonceur->setVerified(true);
        $annonceur->setTel($faker->phoneNumber);
        $annonceur->setAdresse($faker->streetAddress);
        $annonceur->setEntrepriseName($faker->company);
        $annonceur->setEntrepriseLink($faker->url);
        $manager->persist($annonceur);

        $manager->flush();
    }
}
