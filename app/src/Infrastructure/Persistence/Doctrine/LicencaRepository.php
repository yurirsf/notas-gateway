<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Entity\Licenca;
use App\Domain\Repository\LicencaRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class LicencaRepository implements LicencaRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Licenca $licenca): void
    {
        $this->entityManager->persist($licenca);
        $this->entityManager->flush();
    }

    public function findByTokenIntegracao(string $tokenIntegracao): ?Licenca
    {
        $repo = $this->entityManager->getRepository(Licenca::class);
        $uuid = Uuid::fromString($tokenIntegracao);

        return $repo->findOneBy(['tokenIntegracao' => $uuid]);
    }

    public function findByLicenca(string $licenca): ?Licenca
    {
        $repo = $this->entityManager->getRepository(Licenca::class);

        return $repo->findOneBy(['licenca' => $licenca]);
    }
}
