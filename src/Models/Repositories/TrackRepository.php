<?php

namespace App\Models\Repositories;

use App\Models\Infrastructure\DBManager;


class TrackRepository
{
    private $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }

    public function getViewCountByArticleId(int $articleId): int {
        $stmt = $this->pdo->prepare("SELECT view_count FROM track WHERE article_id = :article_id");
        $stmt->execute(['article_id' => $articleId]);
        $result = $stmt->fetch();
        return $result ? (int)$result['view_count'] : 0;
    }

    public function deleteTrackByArticleId(int $articleId): void {
        $stmt = $this->pdo->prepare("DELETE FROM track WHERE article_id = :article_id");
        $stmt->execute(['article_id' => $articleId]);
    }
}
