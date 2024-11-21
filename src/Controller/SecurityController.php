<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private SerializerInterface $serializer)
    {
    }

    #[Route('/registration', name: 'registration', methods: 'POST')]
public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
{
    $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
    $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
    $user->setCreatedAt(new DateTimeImmutable());

    $this->manager->persist($user);
    $this->manager->flush();

    return new JsonResponse(
        ['user'  => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()],
        Response::HTTP_CREATED
    );
}
}

