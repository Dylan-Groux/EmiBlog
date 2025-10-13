<?php

namespace App\Models\Repositories;

use App\Models\Abstract\AbstractManager\AbstractEntityManager;
use App\Models\Entities\Article;
use App\Models\Infrastructure\DBManager;
use App\Models\Exceptions\ValidationException;
use App\Services\ValidationService;
use PDO;

/**
 * Classe qui gère les articles.
 */
class ArticleRepository extends AbstractEntityManager
{
    private PDO $pdo;
    private TrackRepository $trackRepository;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
        $this->trackRepository = new TrackRepository($this->pdo);
    }

    /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles() : array
    {
        $sql = "SELECT * FROM article";
        $result = $this->pdo->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = new Article($article);
        }
        return $articles;
    }
    
    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id) : ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch();
        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return int : l'id de l'article ajouté ou modifié.
     */
    public function addOrUpdateArticle(Article $article) : int
    {
        if ($article->getId() == -1) {
            return $this->addArticle($article); // <-- retourne l'ID créé
        } elseif ($article->getId() > 0) {
            $this->updateArticle($article);
            return $article->getId();
        } else {
            throw new \InvalidArgumentException("L'id de l'article est invalide.");
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return int : l'id de l'article ajouté.
     */
    public function addArticle(Article $article) : int
    {
        // 1. Insérer l'article sans id_track
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->pdo->prepare($sql)->execute([
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
        $articleId = $this->pdo->lastInsertId();

        // 2. Créer la ligne de tracking liée à l'article
        $trackSQL = "INSERT INTO track (view_count, created_at, article_id) VALUES (0, NOW(), :article_id)";
        $this->pdo->prepare($trackSQL)->execute([
            'article_id' => $articleId
        ]);
        $idTrack = $this->pdo->lastInsertId();

        // 3. Mettre à jour l'article avec l'id_track
        $updateSQL = "UPDATE article SET id_track = :id_track WHERE id = :id";
        $this->pdo->prepare($updateSQL)->execute([
            'id_track' => $idTrack,
            'id' => $articleId
        ]);

        return $articleId;
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article) : void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'id' => $article->getId()
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id) : void
    {
        //TODO : Supprimer la ligne de tracking associée car ON CASCADE ne fonctionne pas
        $this->trackRepository->deleteTrackByArticleId($id);
        $sql = "DELETE FROM article WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
