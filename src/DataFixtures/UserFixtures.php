<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

   /** @throws Exception */
public function load(ObjectManager $manager): void
{
    for ($i = 1; $i <= 20; $i++) {
        $user = (new User())
            ->setEmail("test{$i}@example.com")  // Pour éviter les doublons d'email
            ->setRoles(['ROLE_USER'])          // Correction ici
            ->setCreatedAt(new DateTimeImmutable());

        $user->setPassword($this->passwordHasher->hashPassword($user, 'password' . $i));

        $manager->persist($user);
    }
    $manager->flush();
}

}
