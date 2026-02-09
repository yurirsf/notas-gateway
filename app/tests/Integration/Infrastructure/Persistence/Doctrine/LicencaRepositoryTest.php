<?php
declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Persistence\Doctrine;

use App\Domain\Entity\Licenca;
use App\Domain\Repository\LicencaRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LicencaRepositoryTest extends KernelTestCase
{
    private LicencaRepositoryInterface $repository;

    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->repository    = $kernel->getContainer()->get(LicencaRepositoryInterface::class);

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->updateSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    protected function tearDown(): void
    {
        $this->entityManager->clear();

        parent::tearDown();
    }

    public function testSaveAndFindByTokenIntegracao(): void
    {
        $id             = Uuid::uuid4();
        $tokenIntegracao = Uuid::uuid4();
        $licenca        = new Licenca($id, 'empresa-test', $tokenIntegracao);

        $this->repository->save($licenca);

        $found = $this->repository->findByTokenIntegracao($tokenIntegracao->toString());
        $this->assertInstanceOf(Licenca::class, $found);
        $this->assertSame($id->toString(), $found->getId()->toString());
        $this->assertSame('empresa-test', $found->getLicenca());
    }

    public function testFindByTokenIntegracaoReturnsNullWhenNotFound(): void
    {
        $found = $this->repository->findByTokenIntegracao(Uuid::uuid4()->toString());
        $this->assertNull($found);
    }

    public function testFindByLicenca(): void
    {
        $licenca = new Licenca(Uuid::uuid4(), 'empresa-unica', Uuid::uuid4());
        $this->repository->save($licenca);

        $found = $this->repository->findByLicenca('empresa-unica');
        $this->assertInstanceOf(Licenca::class, $found);
        $this->assertSame('empresa-unica', $found->getLicenca());
    }

    public function testFindByLicencaReturnsNullWhenNotFound(): void
    {
        $this->assertNull($this->repository->findByLicenca('inexistente'));
    }
}
