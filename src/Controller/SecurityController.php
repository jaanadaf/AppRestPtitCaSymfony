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
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private SerializerInterface $serializer)
    {
    }

    #[Route('/registration', name: 'registration', methods: 'POST')]
/**
 * @OA\Post(
 *     path="/api/registration",
 *     summary="Inscription d'un nouvel utilisateur",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="StrongPassword123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Utilisateur inscrit avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="user", type="string", example="user@example.com"),
 *             @OA\Property(property="apiToken", type="string", example="31a097543f123489a097543f123489"),
 *             @OA\Property(property="roles", type="array", @OA\Items(type="string", example="ROLE_USER"))
 *         )
 *     )
 * )
 */


    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
        $user->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse(

            ['user' => $user->getUserIdentifier(),
             'apiToken' => $user->getApiToken(),
             'roles' => $user->getRoles()],

            Response::HTTP_CREATED
        );
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ]);
    }
}