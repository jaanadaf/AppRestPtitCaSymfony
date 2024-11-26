<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/restaurant', name: 'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private RestaurantRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    #[Route(methods: ['POST'])]

   
    public function new(Request $request): JsonResponse
    {
         /** @OA\POST(
     *     path="/api/restaurant",
     *     summary="Créer un restaurant",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données du restaurant à créer",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Nom du restaurant"),
     *             @OA\Property(property="description", type="string", example="Description du restaurant"),
     *             @OA\Property(property="createdAt", type="string", format="date-time")
     *         )
     *     )
     * )
     */
        $restaurant = $this->serializer->deserialize($request->getContent(), Restaurant::class, 'json');
        $restaurant->setCreatedAt(new DateTimeImmutable());
        $restaurant->setUpdatedAt(new DateTimeImmutable()); // N'oubliez pas de définir "updatedAt"

        $this->manager->persist($restaurant);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($restaurant, 'json');
        $location = $this->urlGenerator->generate(
            'app_api_restaurant_show',
            ['id' => $restaurant->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    

    
    public function show(int $id): Response
    {
        /** @OA\Get(
     *     path="/api/restaurant/{id}",
     *     summary="Afficher un restaurant par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du restaurant à afficher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurant trouvé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Nom du restaurant"),
     *             @OA\Property(property="description", type="string", example="Description du restaurant"),
     *             @OA\Property(property="createdAt", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant non trouvé"
     *     )
     * )
     */
        $restaurant = $this->repository->findOneBy(['id' => $id]);

        if ($restaurant) {
            $responseData = $this->serializer->serialize($restaurant, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(['data' => null], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        /**
     * @OA\Put(
     *     path="/api/restaurant/{id}",
     *     summary="Mettre à jour un restaurant",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du restaurant à mettre à jour",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données du restaurant à mettre à jour",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Restaurant Le Gourmet"),
     *             @OA\Property(property="description", type="string", example="Restaurant offrant des plats gastronomiques"),
     *             @OA\Property(property="updatedAt", type="string", format="date-time", example="2024-11-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurant mis à jour avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant non trouvé"
     *     )
     * )
     */
        $restaurant = $this->repository->findOneBy(['id' => $id]);

        if ($restaurant) {
            $restaurant = $this->serializer->deserialize(
                $request->getContent(),
                Restaurant::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant]
            );

            $restaurant->setUpdatedAt(new DateTimeImmutable());
            $this->manager->flush();

            return new JsonResponse(['data' => null], Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(['data' => null], Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
          /**
     * @OA\Delete(
     *     path="/api/restaurant/{id}",
     *     summary="Supprimer un restaurant",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du restaurant à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Restaurant supprimé avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant non trouvé"
     *     )
     * )
     */
        $restaurant = $this->repository->findOneBy(['id' => $id]);

        if ($restaurant) {
            $this->manager->remove($restaurant);
            $this->manager->flush();
            return new JsonResponse(['data' => null], Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(['data' => null], Response::HTTP_NOT_FOUND);
    }
}