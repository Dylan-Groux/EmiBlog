<?php

namespace App\Models\Repositories\ErrorCase;

use App\Models\Entities\Comment;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

class CommentRepositoryErrorCaseTest extends TestCase
{
    private function createCommentRepositoryMock()
    {
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock->method('fetch')->willReturn(false); // Simule aucun résultat trouvé

        $pdoMock = $this->createMock(\PDO::class);
        $pdoMock->method('prepare')->willReturn($statementMock);

        return new \App\Models\Repositories\CommentRepository($pdoMock);
    }

    public function testGetCommentByIdNotFound()
    {
        $repo = $this->createCommentRepositoryMock(null, $this->createMock(PDOStatement::class));
        $comment = $repo->getCommentById(999); // ID inexistant
        $this->assertNull($comment);
    }

    public function testDeleteCommentByIdNotFound()
    {
        $repo = $this->createCommentRepositoryMock(null, $this->createMock(PDOStatement::class));
        $comment = new Comment(['id' => 999]); // ID inexistant
        $result = $repo->deleteComment($comment);
        $this->assertFalse($result);
    }
}
