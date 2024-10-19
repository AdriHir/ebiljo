<?php


namespace App\EventListener;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Uid\Uuid;

class UserListener
{
    public function prePersist(User $user, LifecycleEventArgs $event): void
    {
        if ($user->getUuid() === null) {
            $user->setUuid(Uuid::v4());
        }
    }
}