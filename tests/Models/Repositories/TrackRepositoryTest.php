<?php

namespace Tests\Models\Repositories;

use PHPUnit\Framework\TestCase;
use App\Models\Repositories\TrackRepository;
use PDO;
use PDOStatement;

class TrackRepositoryTest extends TestCase
{
    private TrackRepository $trackRepository;

    protected function createTrackRepositoryMock(mixed $fetchReturn): void
    {
        $pdoMock = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn($fetchReturn);
        $statementMock->method('execute')->willReturn(true);

        $pdoMock->method('prepare')->willReturn($statementMock);
        $this->trackRepository = new TrackRepository($pdoMock);
    }

    public function testGetViewCountByArticleIdReturnsExpectedCount()
    {
        $this->createTrackRepositoryMock(['view_count' => 42]);
        $articleId = 1;
        $expectedViewCount = 42;

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn(['view_count' => $expectedViewCount]);

        $viewCount = $this->trackRepository->getViewCountByArticleId($articleId);
        $this->assertEquals($expectedViewCount, $viewCount);
    }

    public function testGetViewCountByArticleIdDoesNotReturnUnexpectedCount()
    {
        $this->createTrackRepositoryMock(['view_count' => 42]);
        $articleId = 1;
        $expectedViewCount = 4844484; // Valeur non réaliste pour tester le cas

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn(false);

        $viewCount = $this->trackRepository->getViewCountByArticleId($articleId);
        $this->assertNotEquals($expectedViewCount, $viewCount);
    }

    public function testGetViewCountByArticleReturnNullIfNotFound()
    {
        $this->createTrackRepositoryMock(false);
        $articleId = 1;

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn(false);

        $viewCount = $this->trackRepository->getViewCountByArticleId($articleId);
        $this->assertEquals(null, $viewCount);
    }

    public function testGetViewByArticleIdThrowsDatabaseExceptionOnError()
    {
        $pdoMock = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pdoMock->method('prepare')->will($this->throwException(new \PDOException("Database error")));

        $this->trackRepository = new TrackRepository($pdoMock);

        // Simule une exception lors de l'exécution de la requête
        $this->expectException(\App\Models\Exceptions\DatabaseException::class);

        // Forcer une exception lors de l'appel à getViewCountByArticleId
        $this->trackRepository->getViewCountByArticleId(1);
    }

    public function testDeleteTrackByArticleIdExecutesWithoutError()
    {
        $this->createTrackRepositoryMock(null);
        $articleId = 1;

        // Juste vérifier que la méthode s'exécute sans lancer d'exception
        try {
            $this->trackRepository->deleteTrackByArticleId($articleId);
            $this->assertTrue(true); // Si on arrive ici, c'est que ça a fonctionné
        } catch (\Exception $e) {
            $this->fail("La méthode deleteTrackByArticleId a lancé une exception : " . $e->getMessage());
        }
    }
}
