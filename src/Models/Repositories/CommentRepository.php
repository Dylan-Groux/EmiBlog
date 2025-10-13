<?php

namespace App\Models\Repositories;

use App\Models\Abstract\AbstractManager\AbstractEntityManager;
use App\Models\Entities\Comment;
use App\Models\Infrastructure\DBManager;
use PDO;

/**
 * Cette classe sert à gérer les commentaires.
 */
class CommentRepository extends AbstractEntityManager
{
    private PDO $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?? DBManager::getInstance()->getPdo();
    }

    /**
     * Récupère tous les commentaires d'un article.
     * @param int $idArticle : l'id de l'article.
     * @return array : un tableau d'objets Comment.
     */
    public function getAllCommentsByArticleId(int $idArticle) : array
    {
        $sql = "SELECT * FROM comment WHERE id_article = :idArticle";
        $result = $this->pdo->prepare($sql);
        $result->execute(['idArticle' => $idArticle]);
        $comments = [];

        while ($comment = $result->fetch()) {
            $comments[] = new Comment($comment);
        }
        return $comments;
    }

    /**
     * Récupère un commentaire par son id.
     * @param int $id : l'id du commentaire.
     * @return Comment|null : un objet Comment ou null si le commentaire n'existe pas.
     */
    public function getCommentById(int $id) : ?Comment
    {
        $sql = "SELECT * FROM comment WHERE id = :id";
        $result = $this->pdo->prepare($sql);
        $result->execute(['id' => $id]);
        $comment = $result->fetch();
        if ($comment) {
            return new Comment($comment);
        }
        return null;
    }

    /**
     * Ajoute un commentaire.
     * @param Comment $comment : l'objet Comment à ajouter.
     * @return bool : true si l'ajout a réussi, false sinon.
     */
    public function addComment(Comment $comment) : bool
    {
        $sql = "INSERT INTO comment (pseudo, content, id_article, date_creation) VALUES (:pseudo, :content, :idArticle, NOW())";
        $result = $this->pdo->prepare($sql);
        $result->execute([
            'pseudo' => $comment->getPseudo(),
            'content' => $comment->getContent(),
            'idArticle' => $comment->getIdArticle()
        ]);
        return $result->rowCount() > 0;
    }

    /**
     * Supprime un commentaire.
     * @param Comment $comment : l'objet Comment à supprimer.
     * @return bool : true si la suppression a réussi, false sinon.
     */
    public function deleteComment(Comment $comment) : bool
    {
        $sql = "DELETE FROM comment WHERE id = :id";
        $result = $this->pdo->prepare($sql);
        $result->execute(['id' => $comment->getId()]);
        return $result->rowCount() > 0;
    }

    /**
     * Compte le nombre de commentaires pour un article donné.
     * @param int $articleId : l'id de l'article.
     * @return int : le nombre de commentaires.
     */
    public function getCountCommentsByArticleId(int $articleId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM comment WHERE id_article = :article_id");
        $stmt->execute(['article_id' => $articleId]);
        $result = $stmt->fetch();
        return $result ? (int)$result['count'] : 0;
    }
}
