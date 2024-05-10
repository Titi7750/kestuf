<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $roleAdmin = new User();
        $plainPasswordAdmin = 'adminKestuf';

        $roleAdmin->setEmail('admin@kestuf.com');
        $roleAdmin->setFirstname('Admin');
        $roleAdmin->setLastname('Kestuf');
        $roleAdmin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $roleAdmin,
            $plainPasswordAdmin
        );
        $roleAdmin->setPassword($hashedPassword);
        $manager->persist($roleAdmin);

        for ($i = 1; $i <= 20; $i++) {
            $plainPasswordUser = 'userKestuf' . $i;
            $roleUser = new User();
            $roleUser->setEmail('user' . $i . '@kestuf.com');
            $roleUser->setFirstname('User' . $i);
            $roleUser->setLastname('Kestuf');
            $roleUser->setRoles(['ROLE_USER']);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $roleUser,
                $plainPasswordUser
            );
            $roleUser->setPassword($hashedPassword);
            $manager->persist($roleUser);
            $this->addReference('user-' . $i, $roleUser);
        }

        $manager->flush();
    }
}
