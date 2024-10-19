<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/user', name: 'user_index', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userService->findAllUsers();
        return $this->json($users);
    }

    #[Route('/user/create', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->userService->createUser(
            $data['email'],
            $data['password'], // Assurez-vous d'encoder le mot de passe !
            $data['firstname'],
            $data['lastname']
        );

        return $this->json($user);
    }

    #[Route('/user/{id}', name: 'user_show', methods: ['GET'])]
    public function show($id): Response
    {
        $user = $this->userService->findUserById($id);
        return $this->json($user);
    }

    #[Route('/user/{id}/edit', name: 'user_edit', methods: ['PUT'])]
    public function edit(Request $request, $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userService->findUserById($id);

        if ($user) {
            $user = $this->userService->updateUser($user, $data);
            return $this->json($user);
        }

        return new Response('User not found', Response::HTTP_NOT_FOUND);
    }

    #[Route('/user/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete($id): Response
    {
        $user = $this->userService->findUserById($id);

        if ($user) {
            $this->userService->deleteUser($user);
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

        return new Response('User not found', Response::HTTP_NOT_FOUND);
    }
}