<?php

namespace App\Models\Repositories;

use App\Models\Abstract\AbstractManager\AbstractEntityManager;
use App\Models\Entities\Article;
use App\Models\Infrastructure\DBManager;
use App\Models\Exceptions\ValidationException;
use App\Services\ValidationService;

/**
 * Classe qui gère les articles.
 */
class ArticleRepository extends AbstractEntityManager
{
    private $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
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
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->pdo->prepare($sql)->execute([
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
        return $this->pdo->lastInsertId();
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
        $sql = "DELETE FROM article WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
