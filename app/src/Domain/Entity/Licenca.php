<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'licencas')]
class Licenca
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $licenca;

    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidInterface $tokenIntegracao;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        UuidInterface $id,
        string $licenca,
        UuidInterface $tokenIntegracao
    ) {
        $this->id = $id;
        $this->licenca = $licenca;
        $this->tokenIntegracao = $tokenIntegracao;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getLicenca(): string
    {
        return $this->licenca;
    }

    public function getTokenIntegracao(): UuidInterface
    {
        return $this->tokenIntegracao;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
