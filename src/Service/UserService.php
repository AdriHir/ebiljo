<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Crée un nouvel utilisateur.
     */
    public function createUser(string $email, string $password, string $firstname, string $lastname): User
    {
        $user = new User();
        $user->setUuid(Uuid::v4());
        $user->setEmail($email);

        // Encodage du mot de passe
        $encodedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($encodedPassword);

        $user->setFirstname($firstname);
        $user->setLastname($lastname);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Met à jour les informations d'un utilisateur.
     */
    public function updateUser(User $user, array $data): User
    {
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['password'])) {
            // Encodage du nouveau mot de passe
            $encodedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($encodedPassword);
        }
        if (isset($data['firstname'])) {
            $user->setFirstname($data['firstname']);
        }
        if (isset($data['lastname'])) {
            $user->setLastname($data['lastname']);
        }

        $this->entityManager->flush();

        return $user;
    }

    /**
     * Supprime un utilisateur.
     */
    public function deleteUser(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * Récupère un utilisateur par son email.
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Récupère tous les utilisateurs.
     */
    public function findAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * Récupère un utilisateur par son ID.
     */
    public function findUserById($id): ?User
    {
        return $this->userRepository->find($id);
    }
}