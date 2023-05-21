<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Annonces;
use App\Entity\User;
use Faker\Factory;

class AnnoncesFixtures extends Fixture {
    public function load(ObjectManager $manager): void {
        // $admin = new User();
        // $admin->setEmail('admin@example.com');
        // $admin->setUsername('admin');
        // $admin->setPassword('admin'); // admin
        // $admin->setRoles(['ROLE_ADMIN']);
        // $manager->persist($admin);

        // $user = new User();
        // $user->setEmail('user@example.com');
        // $user->setUsername('user');
        // $user->setPassword('user'); // user
        // $user->setRoles(['ROLE_USER']);
        
        // $faker = Factory::create('fr_FR');
        // for ($i = 0; $i < 5; $i++) {
        //     $annonces = new Annonces();
        //     $annonces->setTitle($faker->sentence(3));
        //     $annonces->setDescription($faker->paragraph(1));
        //     $annonces->setPrice($faker->randomFloat(2, 10, 100));
        //     $manager->persist($annonces);
        // }
        // $manager->flush();
    }
}
