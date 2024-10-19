<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\EntityListeners([UserListener::class])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Identifiant unique de l'utilisateur (généré automatiquement)
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // UUID unique pour chaque utilisateur, généré automatiquement
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $uuid = null;

    /**
     * Adresse e-mail de l'utilisateur.
     * - Unique : True, chaque utilisateur doit avoir un email distinct.
     * - Regex : Vérifie que l'email est valide selon la structure typique d'une adresse e-mail.
     * - NotBlank : L'email ne doit pas être vide.
     */
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Regex("/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/")]
    #[Assert\NotBlank()]
    private ?string $email = null;

    // Liste des rôles de l'utilisateur
    #[ORM\Column]
    private array $roles = [];

    // Mot de passe de l'utilisateur (hâché)
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Prénom de l'utilisateur.
     * - NotBlank : Le prénom ne doit pas être vide.
     * - Regex : Le prénom doit contenir uniquement des lettres (majuscule et minuscule).
     * - Length : La longueur du prénom doit être entre 2 et 50 caractères.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Regex("/^[a-zA-Z]+$/")]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $firstname = null;

    /**
     * Nom de famille de l'utilisateur.
     * - NotBlank : Le nom de famille ne doit pas être vide.
     * - Regex : Le nom de famille doit contenir uniquement des lettres (majuscule et minuscule).
     * - Length : La longueur du nom de famille doit être entre 2 et 50 caractères.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Regex("/^[a-zA-Z]+$/")]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $lastname = null;

    // Date de création de l'utilisateur (initialisé dans le listener)
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    // Aucun constructeur n'est nécessaire, car tout est géré par le listener

    // Getter pour l'identifiant unique
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour l'UUID
    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    // Setter pour l'UUID
    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    // Getter pour l'email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Setter pour l'email
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    // Identifier visuel qui représente l'utilisateur (pour l'authentification)
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // Getter pour les rôles de l'utilisateur
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Garantit que chaque utilisateur a au moins le rôle ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    // Setter pour les rôles de l'utilisateur
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    // Getter pour le mot de passe
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Setter pour le mot de passe
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    // Permet d'effacer les informations sensibles temporaires de l'utilisateur
    public function eraseCredentials(): void
    {
        // Si vous stockez des données temporaires sensibles sur le user, effacez-les ici
        // $this->plainPassword = null;
    }

    // Getter pour le prénom
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    // Setter pour le prénom
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    // Getter pour le nom de famille
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    // Setter pour le nom de famille
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    // Getter pour la date de création
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    // Setter pour la date de création
    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }
}