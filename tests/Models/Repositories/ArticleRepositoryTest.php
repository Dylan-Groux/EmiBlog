<?php

namespace Tests\Models\Repositories;

use PHPUnit\Framework\TestCase;
use App\Models\Entities\Article;
use App\Models\Repositories\ArticleRepository;
use PDO;
use PDOStatement;

class ArticleRepositoryTest extends TestCase
{
    private function createArticleRepositoryMock(array $articleData = null, $queryReturns = null)
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn($articleData ?? [
            'id' => 1,
            'id_user' => 1,
            'title' => 'Test',
            'content' => 'Contenu test'
        ]);
        $statementMock->method('execute')->willReturn(true);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('query')->willReturn($queryReturns ?? $statementMock);
        $pdoMock->method('prepare')->willReturn($statementMock);

        return new ArticleRepository($pdoMock);
    }

    public function testAddArticleAndGetById()
    {
        $repo = $this->createArticleRepositoryMock();

        $article = new Article([
            'id' => -1,
            'id_user' => 1,
            'title' => 'Test',
            'content' => 'Contenu test'
        ]);

        $repo->addArticle($article);

        // Récupère l'article ajouté (supposons qu'il a l'id 1)
        $added = $repo->getArticleById(1);
        $this->assertInstanceOf(Article::class, $added);
        $this->assertEquals('Test', $added->getTitle());
    }

    public function testGetAllArticles()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')
            ->willReturnOnConsecutiveCalls(
                ['id' => 1, 'id_user' => 1, 'title' => 'Test', 'content' => 'Contenu test'],
                false // Fin de la boucle
            );

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('query')->willReturn($statementMock);

        $repo = new ArticleRepository($pdoMock);
        $articles = $repo->getAllArticles();
        $this->assertIsArray($articles);
        $this->assertInstanceOf(Article::class, $articles[0]);
    }

    public function testUpdateArticle()
    {
        $updatedData = [
            'id' => 1,
            'id_user' => 1,
            'title' => 'Titre modifié',
            'content' => 'Contenu test'
        ];

        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn($updatedData);
        $statementMock->method('execute')->willReturn(true);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $repo = new ArticleRepository($pdoMock);
        $article = new Article($updatedData);
        $repo->updateArticle($article);

        $result = $repo->getArticleById(1);
        $this->assertEquals('Titre modifié', $result->getTitle());
    }

    public function testDeleteArticle()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn(false); // Simule la non-présence

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $repo = new ArticleRepository($pdoMock);
        $repo->deleteArticle(1);

        $result = $repo->getArticleById(1);
        $this->assertNull($result);
    }
}
