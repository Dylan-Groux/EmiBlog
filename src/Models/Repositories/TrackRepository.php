<?php

namespace App\Models\Repositories;

use App\Models\Infrastructure\DBManager;
use App\Models\Abstract\AbstractManager\AbstractEntityManager;
use PDO;
use App\Models\Exceptions\DatabaseException;

/** Repository pour la gestion des pistes de lecture */
class TrackRepository extends AbstractEntityManager
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }

    /**
     * Récupère le nombre de vues pour un article donné.
     * @param int $articleId : l'id de l'article.
     * @return int|null : le nombre de vues.
     */
    public function getViewCountByArticleId(int $articleId): null|int {
        try {
            $stmt = $this->pdo->prepare("SELECT view_count FROM track WHERE article_id = :article_id");
            $stmt->execute(['article_id' => $articleId]);
            $result = $stmt->fetch();
            return $result ? (int)$result['view_count'] : null;
        } catch (\Exception $e) {
            error_log("Erreur lors de la récupération du nombre de vues pour l'article ID $articleId : " . $e->getMessage());
            throw new DatabaseException("Erreur lors de la récupération du nombre de vues.");
        }
    }

    /**
     * Supprime les entrées de la table track pour un article donné.
     * @param int $articleId : l'id de l'article.
     * @return void
     */
    public function deleteTrackByArticleId(int $articleId): void {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM track WHERE article_id = :article_id");
            $stmt->execute(['article_id' => $articleId]);
        } catch (\Exception $e) {
            error_log("Erreur lors de la suppression des pistes pour l'article ID $articleId : " . $e->getMessage());
        }
    }
}
