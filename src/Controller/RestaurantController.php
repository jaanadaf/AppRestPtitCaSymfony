<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/restaurant', name: 'app_api_restaurant')]

class RestaurantController extends AbstractController
{

    #[Route(name: 'new', methods: 'POST')]
    public function new() : Response
    {
        $restaurant = new Restaurant();
        $restaurant->setName('PtitCahoua');
        $restaurant->setDescription('Cette qualité et ce goût par le chef Jaadar');
        $restaurant->setCreatedAt(new DateTimeImmutable());
        
        // à stocker en base de données
        return $this->json(
            ['message' => "Restaurant resource created with {$restaurant->getId()} id"],
            Response::HTTP_CREATED,
        );
    }

    #[Route('/show', name: 'show', methods: 'GET')]
    public function show() : Response
    {

        return $this->json(['message'=> 'Restaurant de MDD']);
    
        
    }

    #[Route('/', name: 'edit', methods: 'PUT')]
    public function edit() : Response
    {
        
    }

    #[Route('/', name: 'delete', methods: 'DELETE')]
    public function delete() : Response
    {
        
    }
}
