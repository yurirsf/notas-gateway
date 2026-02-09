<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Licenca;

interface LicencaRepositoryInterface
{
    public function save(Licenca $licenca): void;

    public function findByTokenIntegracao(string $tokenIntegracao): ?Licenca;

    public function findByLicenca(string $licenca): ?Licenca;
}
