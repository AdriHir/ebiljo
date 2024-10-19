<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Uid\Uuid;

class UserListener
{
    /**
     * Cette méthode est appelée avant la persistance d'un nouvel utilisateur en base de données.
     * Elle vérifie et initialise l'UUID et la date de création.
     *
     * @param User $user L'entité utilisateur sur le point d'être persistée.
     * @param LifecycleEventArgs $event Les arguments liés à l'événement du cycle de vie.
     */
    public function prePersist(User $user, LifecycleEventArgs $event): void
    {
        // Si l'utilisateur n'a pas d'UUID, en générer un nouveau
        if ($user->getUuid() === null) {
            $user->setUuid(Uuid::v4());
        }

        // Si l'utilisateur n'a pas de date de création, en définir une nouvelle
        if ($user->getCreatedAt() === null) {
            $user->setCreatedAt(new \DateTimeImmutable());
        }
    }
}