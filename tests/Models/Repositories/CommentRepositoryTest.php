<?php

namespace Tests\Models\Repositories;

use PHPUnit\Framework\TestCase;
use App\Models\Entities\Comment;
use App\Models\Repositories\CommentRepository;
use PDO;
use PDOStatement;


class CommentRepositoryTest extends TestCase
{
    private function createCommentRepositoryMock(array $commentData = null, $queryReturns = null)
    {
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock->method('fetch')->willReturn($commentData ?? [
            'id' => 1,
            'pseudo' => 'TestUser',
            'content' => 'Contenu du commentaire',
            'id_article' => 1,
            'date_creation' => '2024-01-01 12:00:00'
        ]);
        $statementMock->method('execute')->willReturn(true);
        $statementMock->method('rowCount')->willReturn(1);

        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('query')->willReturn($queryReturns ?? $statementMock);
        $pdoMock->method('prepare')->willReturn($statementMock);

        return new \App\Models\Repositories\CommentRepository($pdoMock);
    }

    public function testAddCommentAndGetById()
    {
        $repo = $this->createCommentRepositoryMock();

        $comment = new \App\Models\Entities\Comment([
            'id' => -1,
            'pseudo' => 'TestUser',
            'content' => 'Contenu du commentaire',
            'id_article' => 1
        ]);

        $repo->addComment($comment);

        // Récupère le commentaire ajouté (supposons qu'il a l'id 1)
        $added = $repo->getCommentById(1);
        $this->assertInstanceOf(Comment::class, $added);
        $this->assertEquals('TestUser', $added->getPseudo());
        $this->assertEquals('Contenu du commentaire', $added->getContent());
    }

    public function testGetAllCommentsByArticleId()
    {
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')
            ->willReturnOnConsecutiveCalls(
                ['id' => 1, 'pseudo' => 'User1', 'content' => 'Commentaire 1', 'id_article' => 1, 'date_creation' => '2024-01-01 12:00:00'],
                ['id' => 2, 'pseudo' => 'User2', 'content' => 'Commentaire 2', 'id_article' => 1, 'date_creation' => '2024-01-02 13:00:00'],
                false // Fin de la boucle
            );

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        $repo = new CommentRepository($pdoMock);
        $comments = $repo->getAllCommentsByArticleId(1);

        $this->assertIsArray($comments);
        $this->assertCount(2, $comments);
        $this->assertInstanceOf(Comment::class, $comments[0]);
        $this->assertEquals('User1', $comments[0]->getPseudo());
        $this->assertEquals('Commentaire 1', $comments[0]->getContent());
        $this->assertInstanceOf(Comment::class, $comments[1]);
        $this->assertEquals('User2', $comments[1]->getPseudo());
        $this->assertEquals('Commentaire 2', $comments[1]->getContent());
    }

    public function testDeleteComment()
    {
        $repo = $this->createCommentRepositoryMock();

        $comment = new Comment([
            'id' => 1,
            'pseudo' => 'TestUser',
            'content' => 'Contenu du commentaire',
            'id_article' => 1
        ]);

        $result = $repo->deleteComment($comment);
        $this->assertTrue($result, "La méthode deleteComment devrait retourner false si le commentaire n'existe pas, mais elle retourne true.");
    }
}
