<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class CharacterController extends AbstractController
{
    private CharacterRepository $repository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $manager = $doctrine->getManager();
        $this->repository = $manager->getRepository(Character::class);
    }

    #[Route('/characters', name: 'character_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $characters = $this->repository->findAll();

        return $this->json($characters);
    }

    #[Route('/characters/{slug}', name: 'character_info', methods: ['GET'], requirements: ['slug' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'])]
    public function show(Character $character): JsonResponse
    {
        return $this->json($character);
    }

    #[Route('/characters', name: 'characters_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $this->repository->save($data);

        return $this->json([], Response::HTTP_CREATED);
    }

    #[Route('/characters/{slug}', name: 'character_update', methods: ['PUT', 'PATCH'], requirements: ['slug' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'])]
    public function update(Character $character): JsonResponse
    {
        return $this->json($character, Response::HTTP_NO_CONTENT);
    }

    #[Route('/characters/{slug}', name: 'character_delete', methods: ['DELETE'], requirements: ['slug' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'])]
    public function destroy(string $slug): JsonResponse
    {
        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
