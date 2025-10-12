<?php

namespace App\Controllers;

use App\Models\Repositories\ArticleRepository;
use App\Models\Repositories\CommentRepository;
use App\Models\Entities\Comment;
use App\Services\Utils;
use App\Library\Route;
use App\Library\AbstractController;
use App\Models\Exceptions\ValidationException;
use App\Services\ViewTracker;

class CommentController extends AbstractController
{
    /**
     * Ajoute un commentaire.
     * @return void
     */
    #[Route(path: COMMENT_SUBMIT_ROUTE, method: "POST")]
    public function addComment() : void
    {
        // Récupération des données du formulaire.
        $pseudo = Utils::request("pseudo");
        $content = Utils::request("content");
        $idArticle = Utils::request("idArticle");

        // On vérifie que les données sont valides.
        if (empty($pseudo) || empty($content) || empty($idArticle)) {
            throw new ValidationException("Tous les champs sont obligatoires. 3");
        }

        // On vérifie que l'article existe.
        $articleRepository = new ArticleRepository();
        $article = $articleRepository->getArticleById($idArticle);
        if (!$article) {
            throw new ValidationException("L'article demandé n'existe pas.");
        }

        // On crée l'objet Comment.
        $comment = new Comment([
            'pseudo' => $pseudo,
            'content' => $content,
            'idArticle' => $idArticle
        ]);

        // On ajoute le commentaire.
        $commentRepository = new CommentRepository();
        $result = $commentRepository->addComment($comment);

        // On vérifie que l'ajout a bien fonctionné.
        if (!$result) {
            throw new ValidationException("Une erreur est survenue lors de l'ajout du commentaire.");
        }

        // On redirige vers la page de l'article.
        Utils::redirect( ARTICLE_ROUTE, ['id' => $idArticle]);
    }
}
