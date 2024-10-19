<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class UserTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // évite les fuites de mémoire
    }

    public function testUserEntity()
    {
        $user = new User();
        $uuid = Uuid::v4();
        $user->setUuid($uuid);
        $user->setEmail('test@example.com');
        $user->setPassword('password');
        $user->setFirstname('John');
        $user->setLastname('Doe');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Vérifications
        $this->assertSame($uuid, $user->getUuid());
        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame('John', $user->getFirstname());
        $this->assertSame('Doe', $user->getLastname());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
    }
}